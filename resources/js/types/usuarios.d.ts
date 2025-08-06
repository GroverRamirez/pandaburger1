export interface User {
  id: number
  nombre: string
  correo_electronico: string
  telefono?: string
  rol_id: number
  rol?: {
    id: number
    nombre: string
  }
  last_login_at?: string
  deleted_at?: string
  avatar?: string
}

export interface Role {
  id: number
  nombre: string
}
