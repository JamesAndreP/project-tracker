import api from './api'

export interface Project {
  id: number
  client_name: string
  project_name: string
  description: string | null
  status: 'Planning' | 'In Progress' | 'On Hold' | 'Completed'
  priority: 'Low' | 'Medium' | 'High'
  start_date: string | null
  due_date: string | null
  created_at: string
  updated_at: string
}

export interface Stats {
  total: number
  inProgress: number
  completed: number
}

export interface FetchedStats {
  total: number
  in_progress: number
  completed: number
}

export interface ProjectFormData {
  client_name: string
  project_name: string
  description?: string
  status?: 'Planning' | 'In Progress' | 'On Hold' | 'Completed'
  priority?: 'Low' | 'Medium' | 'High'
  start_date?: string
  due_date?: string
}

export interface PaginatedProjectsResponse {
  data: Project[]
  current_page: number
  last_page: number
  per_page: number
  total: number
}

export const projectService = {
  async getAll(): Promise<Project[]> {
    const response = await api.get<Project[]>('/projects')
    return response.data
  },

  async getPaginated(page: number = 1, perPage: number = 20, search: string = ''): Promise<PaginatedProjectsResponse> {
    const response = await api.get<PaginatedProjectsResponse>('/projects-paginated', {
      params: { page, per_page: perPage, search },
    })
    return response.data
  },

  async getById(id: number): Promise<Project> {
    const response = await api.get<Project>(`/projects/${id}`)
    return response.data
  },

  async create(data: ProjectFormData): Promise<Project> {
    const response = await api.post<Project>('/projects', data)
    return response.data
  },

  async update(id: number, data: ProjectFormData): Promise<Project> {
    const response = await api.put<Project>(`/projects/${id}`, data)
    return response.data
  },

  async delete(id: number): Promise<void> {
    await api.delete(`/projects/${id}`)
  },

  async getStats(): Promise<FetchedStats> {
    const response = await api.get<FetchedStats>('/stats')
    return response.data
  },
}
