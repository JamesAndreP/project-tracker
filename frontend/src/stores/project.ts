import { defineStore } from 'pinia'
import { ref, computed } from 'vue'
import { projectService, type Project, type ProjectFormData, type PaginatedProjectsResponse, type Stats } from '@/services/project'

/* eslint-disable @typescript-eslint/no-explicit-any */
export const useProjectStore = defineStore('project', () => {
  const projects = ref<Project[]>([])
  const stats = ref<Stats>({ total: 0, inProgress: 0, completed: 0 })
  const currentProject = ref<Project | null>(null)
  const loading = ref(false)
  const loadingMore = ref(false)
  const error = ref<string | null>(null)
  
  // Pagination state
  const currentPage = ref(1)
  const lastPage = ref(1)
  const perPage = ref(18)
  const recentProjectPage = ref(6)
  const total = ref(0)
  const searchQuery = ref('')
  const hasMore = computed(() => currentPage.value < lastPage.value)

  async function fetchAll() {
    loading.value = true
    error.value = null
    try {
      projects.value = await projectService.getAll()
      return true
    } catch (err: any) {
      error.value = err.response?.data?.message || 'Failed to fetch projects'
      return false
    } finally {
      loading.value = false
    }
  }

  async function fetchStats() {
    loading.value = true
    error.value = null
    try {
      const fetchedStats = await projectService.getStats()
      stats.value = {
        total: fetchedStats.total,
        inProgress: fetchedStats.in_progress,
        completed: fetchedStats.completed,
      }
      return true
    } catch (err: any) {
      error.value = err.response?.data?.message || 'Failed to fetch stats'
      return false
    } finally {
      loading.value = false
    }
  }

  async function fetchPaginated(page: number = 1, reset: boolean = false, isRecent: boolean = false) {
    const isFirstPage = reset || page === 1

    const requestingPerPage = isRecent ? recentProjectPage.value : perPage.value

    // Only the first page / a fresh search should hide the list
    if (isFirstPage) {
      loading.value = true
    } else {
      loadingMore.value = true
    }
    error.value = null

    try {
      const response = await projectService.getPaginated(page, requestingPerPage, searchQuery.value)

      if (isFirstPage) {
        projects.value = response.data
      } else {
        projects.value = [...projects.value, ...response.data]
      }

      currentPage.value = response.current_page
      lastPage.value = response.last_page
      total.value = response.total
      return true
    } catch (err: any) {
      error.value = err.response?.data?.message || 'Failed to fetch projects'
      return false
    } finally {
      loading.value = false
      loadingMore.value = false
    }
  }

  function setSearchQuery(query: string) {
    searchQuery.value = query
    resetPagination()
  }

  async function loadMore() {
    if (!hasMore.value || loading.value || loadingMore.value) return
    await fetchPaginated(currentPage.value + 1)
  }

  async function fetchById(id: number) {
    loading.value = true
    error.value = null
    try {
      currentProject.value = await projectService.getById(id)
      return true
    } catch (err: any) {
      error.value = err.response?.data?.message || 'Failed to fetch project'
      return false
    } finally {
      loading.value = false
    }
  }

  async function create(data: ProjectFormData) {
    loading.value = true
    error.value = null
    try {
      const newProject = await projectService.create(data)
      projects.value.unshift(newProject)
      return newProject
    } catch (err: any) {
      error.value = err.response?.data?.message || 'Failed to create project'
      return null
    } finally {
      loading.value = false
    }
  }

  async function update(id: number, data: ProjectFormData) {
    loading.value = true
    error.value = null
    try {
      const updatedProject = await projectService.update(id, data)
      const index = projects.value.findIndex((p) => p.id === id)
      if (index !== -1) {
        projects.value[index] = updatedProject
      }
      if (currentProject.value?.id === id) {
        currentProject.value = updatedProject
      }
      return updatedProject
    } catch (err: any) {
      error.value = err.response?.data?.message || 'Failed to update project'
      return null
    } finally {
      loading.value = false
    }
  }

  async function remove(id: number) {
    loading.value = true
    error.value = null
    try {
      await projectService.delete(id)
      projects.value = projects.value.filter((p) => p.id !== id)
      if (currentProject.value?.id === id) {
        currentProject.value = null
      }
      return true
    } catch (err: any) {
      error.value = err.response?.data?.message || 'Failed to delete project'
      return false
    } finally {
      loading.value = false
    }
  }

  function clearError() {
    error.value = null
  }

  function setCurrentProject(project: Project | null) {
    currentProject.value = project
  }

  function resetPagination() {
    currentPage.value = 1
    lastPage.value = 1
    projects.value = []
  }

  return {
    projects,
    currentProject,
    loading,
    loadingMore,
    error,
    currentPage,
    lastPage,
    perPage,
    recentProjectPage,
    total,
    searchQuery,
    hasMore,
    stats,
    fetchAll,
    fetchStats,
    fetchPaginated,
    loadMore,
    fetchById,
    create,
    update,
    remove,
    clearError,
    setCurrentProject,
    resetPagination,
    setSearchQuery,
  }
})
