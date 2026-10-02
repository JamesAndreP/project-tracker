<template>
  <div class="dashboard-container">
    <nav class="navbar">
      <div class="navbar-brand">
        <router-link to="/dashboard" class="brand-link">
          <h1>Project Tracker</h1>
        </router-link>
      </div>
      <div class="navbar-menu">
        <router-link to="/projects" class="nav-link">Projects</router-link>
        <span class="user-info">{{ authStore.user?.name }}</span>
        <button @click="handleLogout" class="logout-button">Logout</button>
      </div>
    </nav>

    <main class="dashboard-content">
      <div class="welcome-section">
        <h2>Welcome back, {{ authStore.user?.name }}!</h2>
        <p>Manage your projects efficiently</p>
      </div>

      <div class="stats-grid">
        <div class="stat-card">
          <div class="stat-icon">
            <svg width="32" height="32" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
              <rect x="3" y="3" width="18" height="18" rx="2" ry="2"></rect>
              <line x1="12" y1="8" x2="12" y2="16"></line>
              <line x1="8" y1="12" x2="16" y2="12"></line>
            </svg>
          </div>
          <div class="stat-info">
            <h3>Total Projects</h3>
            <p class="stat-value">{{ projectStore.stats.total }}</p>
          </div>
        </div>

        <div class="stat-card">
          <div class="stat-icon">
            <svg width="32" height="32" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
              <circle cx="12" cy="12" r="10"></circle>
              <polyline points="12 6 12 12 16 14"></polyline>
            </svg>
          </div>
          <div class="stat-info">
            <h3>In Progress</h3>
            <p class="stat-value">{{ projectStore.stats.inProgress }}</p>
          </div>
        </div>

        <div class="stat-card">
          <div class="stat-icon">
            <svg width="32" height="32" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
              <path d="M22 11.08V12a10 10 0 1 1-5.93-9.14"></path>
              <polyline points="22 4 12 14.01 9 11.01"></polyline>
            </svg>
          </div>
          <div class="stat-info">
            <h3>Completed</h3>
            <p class="stat-value">{{ projectStore.stats.completed }}</p>
          </div>
        </div>
      </div>

      <div class="projects-section">
        <div class="section-header">
          <h3>Recent Projects</h3>
          <button @click="goToProjects" class="add-project-button">View All Projects</button>
        </div>

        <div v-if="projectStore.loading" class="loading-state">
          <p>Loading projects...</p>
        </div>

        <div v-else-if="projectStore.projects.length === 0" class="empty-state">
          <svg width="64" height="64" viewBox="0 0 24 24" fill="none" stroke="#cbd5e0" stroke-width="1.5">
            <rect x="3" y="3" width="18" height="18" rx="2" ry="2"></rect>
            <line x1="12" y1="8" x2="12" y2="16"></line>
            <line x1="8" y1="12" x2="16" y2="12"></line>
          </svg>
          <p>No projects yet. Create your first project to get started!</p>
        </div>

        <div v-else class="recent-projects">
          <div
            v-for="project in projectStore.projects"
            :key="project.id"
            class="project-item"
            @click="goToProjects"
          >
            <h4>{{ project.project_name }}</h4>
            <p>{{ project.client_name }}</p>
            <span :class="['status-badge', getStatusClass(project.status)]">{{ project.status }}</span>
          </div>
        </div>
      </div>
    </main>
  </div>
</template>

<script setup lang="ts">
import { onMounted } from 'vue'
import { useRouter } from 'vue-router'
import { useAuthStore } from '@/stores/auth'
import { useProjectStore } from '@/stores/project'

const router = useRouter()
const authStore = useAuthStore()
const projectStore = useProjectStore()

onMounted(async () => {
  if (!authStore.user) {
    await authStore.fetchUser()
  }
  projectStore.fetchPaginated(1, true, true)
  projectStore.fetchStats ()
})

const handleLogout = async () => {
  await authStore.logout()
  router.push('/login')
}

const goToProjects = () => {
  router.push('/projects')
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
</script>
