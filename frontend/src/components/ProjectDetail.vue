<template>
  <div class="modal-overlay" @click="$emit('close')">
    <div class="modal-content" @click.stop>
      <div class="modal-header">
        <h2>{{ project?.project_name }}</h2>
        <button @click="$emit('close')" class="close-button">&times;</button>
      </div>

      <div v-if="projectStore.loading" class="loading-state">
        <p>Loading...</p>
      </div>

      <div v-else-if="project" class="project-detail">
        <div class="detail-section">
          <h3>Client</h3>
          <p>{{ project.client_name }}</p>
        </div>

        <div class="detail-section">
          <h3>Description</h3>
          <p>{{ project.description || 'No description' }}</p>
        </div>

        <div class="detail-row">
          <div class="detail-section">
            <h3>Status</h3>
            <span :class="['status-badge', getStatusClass(project.status)]">{{ project.status }}</span>
          </div>

          <div class="detail-section">
            <h3>Priority</h3>
            <span :class="['priority-badge', getPriorityClass(project.priority)]">{{ project.priority }}</span>
          </div>
        </div>

        <div class="detail-row">
          <div class="detail-section">
            <h3>Start Date</h3>
            <p>{{ formatDate(project.start_date) }}</p>
          </div>

          <div class="detail-section">
            <h3>Due Date</h3>
            <p>{{ formatDate(project.due_date) }}</p>
          </div>
        </div>

        <div class="detail-section">
          <h3>Created</h3>
          <p>{{ new Date(project.created_at).toLocaleString() }}</p>
        </div>

        <div class="modal-footer">
          <button @click="handleEdit" class="edit-button">Edit Project</button>
          <button @click="handleDelete" class="delete-button">Delete Project</button>
        </div>
      </div>
    </div>
  </div>
</template>

<script setup lang="ts">
import { ref, onMounted, watch } from 'vue'
import { useProjectStore } from '@/stores/project'

const props = defineProps<{
  projectId: number
}>()

const emit = defineEmits<{
  close: []
  deleted: []
  edit: [project: any]
}>()

const projectStore = useProjectStore()
const project = ref<any>(null)

onMounted(() => {
  loadProject()
})

watch(() => props.projectId, () => {
  loadProject()
})

const loadProject = async () => {
  await projectStore.fetchById(props.projectId)
  project.value = projectStore.currentProject
}

const handleEdit = () => {
  emit('edit', project.value)
}

const handleDelete = async () => {
  if (confirm('Are you sure you want to delete this project?')) {
    const success = await projectStore.remove(props.projectId)
    if (success) {
      emit('deleted')
    }
  }
}

const getStatusClass = (status: string) => {
  const classes: Record<string, string> = {
    'Planning': 'status-planning',
    'In Progress': 'status-in-progress',
    'On Hold': 'status-on-hold',
    'Completed': 'status-completed',
  }
  return classes[status] || ''
}

const getPriorityClass = (priority: string) => {
  const classes: Record<string, string> = {
    'Low': 'priority-low',
    'Medium': 'priority-medium',
    'High': 'priority-high',
  }
  return classes[priority] || ''
}

const formatDate = (date: string | null) => {
  if (!date) return 'Not set'
  return new Date(date).toLocaleDateString()
}
</script>
