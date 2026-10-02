<template>
  <div class="projects-container">
    <nav class="navbar">
      <div class="navbar-brand">
        <router-link to="/dashboard" class="brand-link">
          <h1>Project Tracker</h1>
        </router-link>
      </div>
      <div class="navbar-menu">
        <router-link to="/dashboard" class="nav-link">Dashboard</router-link>
        <span class="user-info">{{ authStore.user?.name }}</span>
        <button @click="handleLogout" class="logout-button">Logout</button>
      </div>
    </nav>

    <main class="projects-content">
      <div class="section-header">
        <h2>Projects</h2>
        <div class="header-actions">
          <div class="search-input">
            <input
              v-model="searchQuery"
              @input="handleSearch"
              type="text"
              placeholder="Search projects..."
            />
            <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
              <circle cx="11" cy="11" r="8"></circle>
              <line x1="21" y1="21" x2="16.65" y2="16.65"></line>
            </svg>
          </div>
          <button @click="openCreateModal" class="add-project-button">+ Add Project</button>
        </div>
      </div>

      <!-- Initial load only: nothing to show yet -->
      <div v-if="projectStore.loading && projectStore.projects.length === 0" class="loading-state">
        <p>Loading projects...</p>
      </div>

      <div v-else-if="!projectStore.loading && projectStore.projects.length === 0" class="empty-state">
        <svg width="64" height="64" viewBox="0 0 24 24" fill="none" stroke="#cbd5e0" stroke-width="1.5">
          <rect x="3" y="3" width="18" height="18" rx="2" ry="2"></rect>
          <line x1="12" y1="8" x2="12" y2="16"></line>
          <line x1="8" y1="12" x2="16" y2="12"></line>
        </svg>
        <p>No projects yet. Create your first project to get started!</p>
      </div>

      <template v-else>
        <div class="projects-grid">
          <div
            v-for="project in projectStore.projects"
            :key="project.id"
            class="project-card"
            @click="viewProject(project.id)"
          >
            <div class="project-header">
              <h3>{{ project.project_name }}</h3>
              <span :class="['status-badge', getStatusClass(project.status)]">{{ project.status }}</span>
            </div>
            <p class="client-name">{{ project.client_name }}</p>
            <p class="description">{{ project.description || 'No description' }}</p>
            <div class="project-footer">
              <span :class="['priority-badge', getPriorityClass(project.priority)]">{{ project.priority }}</span>
              <span class="date-info">{{ formatDate(project.due_date) }}</span>
            </div>
          </div>
        </div>

        <!-- Existing cards stay mounted; indicator sits below the grid -->
        <div v-if="projectStore.loadingMore" class="loading-more">
          Loading more projects...
        </div>
      </template>
    </main>

    <ProjectForm
      v-if="showFormModal"
      :project="editingProject"
      @close="closeFormModal"
      @saved="handleProjectSaved"
    />

    <ProjectDetail
      v-if="selectedProjectId"
      :project-id="selectedProjectId"
      @close="selectedProjectId = null"
      @deleted="handleProjectDeleted"
      @edit="handleEditProject"
    />
  </div>
</template>

<script setup lang="ts">
import { ref, onMounted, onUnmounted } from 'vue'
import { useRouter } from 'vue-router'
import { useAuthStore } from '@/stores/auth'
import { useProjectStore } from '@/stores/project'
import ProjectForm from '@/components/ProjectForm.vue'
import ProjectDetail from '@/components/ProjectDetail.vue'

const router = useRouter()
const authStore = useAuthStore()
const projectStore = useProjectStore()

const selectedProjectId = ref<number | null>(null)
const showFormModal = ref(false)
const editingProject = ref<any>(null)
const searchQuery = ref('')
const searchTimeout = ref<number | null>(null)

onMounted(async () => {
  await projectStore.fetchPaginated(1, true, false)
  window.addEventListener('scroll', handleScroll)
})

onUnmounted(() => {
  window.removeEventListener('scroll', handleScroll)
  if (searchTimeout.value) {
    clearTimeout(searchTimeout.value)
  }
})

const handleLogout = async () => {
  await authStore.logout()
  router.push('/login')
}

const openCreateModal = () => {
  editingProject.value = null
  showFormModal.value = true
}

const viewProject = (id: number) => {
  selectedProjectId.value = id
}

const handleProjectSaved = () => {
  closeFormModal()
  projectStore.resetPagination()
  projectStore.fetchPaginated(1, true, false)
}

const handleProjectDeleted = () => {
  selectedProjectId.value = null
  projectStore.resetPagination()
  projectStore.fetchPaginated(1, true, false)
}

const handleEditProject = (project: any) => {
  selectedProjectId.value = null
  editingProject.value = project
  showFormModal.value = true
}

const closeFormModal = () => {
  showFormModal.value = false
  editingProject.value = null
}

const handleSearch = () => {
  if (searchTimeout.value) {
    clearTimeout(searchTimeout.value)
  }

  searchTimeout.value = window.setTimeout(() => {
    projectStore.setSearchQuery(searchQuery.value)
    projectStore.fetchPaginated(1, true, false)
  }, 500)
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
  if (!date) return 'No due date'
  return new Date(date).toLocaleDateString()
}

const handleScroll = () => {
  const { scrollTop, scrollHeight, clientHeight } = document.documentElement

  if (scrollHeight - scrollTop - clientHeight < 100) {
    projectStore.loadMore()
  }
}
</script>