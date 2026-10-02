<template>
  <div class="modal-overlay" @click="$emit('close')">
    <div class="modal-content" @click.stop>
      <div class="modal-header">
        <h2>{{ isEdit ? 'Edit Project' : 'Create New Project' }}</h2>
        <button @click="$emit('close')" class="close-button">&times;</button>
      </div>

      <form @submit.prevent="handleSubmit" class="project-form">
        <div class="form-group">
          <label for="client_name">Client Name *</label>
          <input
            id="client_name"
            v-model="formData.client_name"
            type="text"
            placeholder="Enter client name"
            required
          />
        </div>

        <div class="form-group">
          <label for="project_name">Project Name *</label>
          <input
            id="project_name"
            v-model="formData.project_name"
            type="text"
            placeholder="Enter project name"
            required
          />
        </div>

        <div class="form-group">
          <label for="description">Description</label>
          <textarea
            id="description"
            v-model="formData.description"
            placeholder="Enter project description"
            rows="3"
          ></textarea>
        </div>

        <div class="form-row">
          <div class="form-group">
            <label for="status">Status</label>
            <select id="status" v-model="formData.status">
              <option value="Planning">Planning</option>
              <option value="In Progress">In Progress</option>
              <option value="On Hold">On Hold</option>
              <option value="Completed">Completed</option>
            </select>
          </div>

          <div class="form-group">
            <label for="priority">Priority</label>
            <select id="priority" v-model="formData.priority">
              <option value="Low">Low</option>
              <option value="Medium">Medium</option>
              <option value="High">High</option>
            </select>
          </div>
        </div>

        <div class="form-row">
          <div class="form-group">
            <label for="start_date">Start Date</label>
            <input
              id="start_date"
              v-model="formData.start_date"
              type="date"
              required
            />
          </div>

          <div class="form-group">
            <label for="due_date">Due Date</label>
            <input
              id="due_date"
              v-model="formData.due_date"
              type="date"
              required
            />
          </div>
        </div>

        <div v-if="projectStore.error" class="error-message">
          {{ projectStore.error }}
        </div>

        <div class="modal-footer">
          <button type="button" @click="$emit('close')" class="cancel-button">Cancel</button>
          <button type="submit" class="submit-button" :disabled="projectStore.loading">
            <span v-if="projectStore.loading">{{ isEdit ? 'Updating...' : 'Creating...' }}</span>
            <span v-else>{{ isEdit ? 'Update Project' : 'Create Project' }}</span>
          </button>
        </div>
      </form>
    </div>
  </div>
</template>

<script setup lang="ts">
import { reactive, watch, computed } from 'vue'
import { useProjectStore } from '@/stores/project'
import type { ProjectFormData, Project } from '@/services/project'

const props = defineProps<{
  show?: boolean
  project?: Project | null
}>()

const projectStore = useProjectStore()

const emit = defineEmits<{
  close: []
  saved: []
}>()

const isEdit = computed(() => !!props.project)

const formData = reactive<ProjectFormData>({
  client_name: '',
  project_name: '',
  description: '',
  status: 'Planning',
  priority: 'Medium',
  start_date: '',
  due_date: '',
})

watch(() => props.project, (newProject) => {
  if (newProject) {
    Object.assign(formData, {
      client_name: newProject.client_name,
      project_name: newProject.project_name,
      description: newProject.description || '',
      status: newProject.status,
      priority: newProject.priority,
      start_date: newProject.start_date || '',
      due_date: newProject.due_date || '',
    })
  } else {
    Object.assign(formData, {
      client_name: '',
      project_name: '',
      description: '',
      status: 'Planning',
      priority: 'Medium',
      start_date: '',
      due_date: '',
    })
  }
}, { immediate: true })

const handleSubmit = async () => {
  projectStore.clearError()
  let result
  if (isEdit.value && props.project) {
    result = await projectStore.update(props.project.id, formData)
  } else {
    result = await projectStore.create(formData)
  }
  if (result) {
    emit('saved')
  }
}
</script>
