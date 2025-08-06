<template>
  <AppLayout :breadcrumbs="breadcrumbs">
    <Head title="Usuarios - Pandaburger" />
    
    <div class="min-h-screen bg-gradient-to-br from-orange-50 to-orange-100 dark:from-gray-900 dark:to-orange-900/10">
      <!-- Header Section -->
      <div class="bg-white dark:bg-gray-800 shadow-lg border-b border-orange-200 dark:border-orange-800">
        <div class="max-w-7xl mx-auto px-6 py-8">
          <div class="flex items-center justify-between">
            <div>
              <h1 class="text-4xl font-bold text-gradient mb-2">
                👥 Usuarios
              </h1>
              <p class="text-lg text-gray-600 dark:text-gray-400">
                Administra el equipo de Pandaburger
              </p>
            </div>
            <button
              @click="showCreateModal = true"
              class="btn-restaurant inline-flex items-center"
            >
              <Plus class="w-5 h-5 mr-2" />
              Nuevo Usuario
            </button>
          </div>
        </div>
      </div>

      <!-- Content Section -->
      <div class="max-w-7xl mx-auto px-6 py-8">
        <!-- Stats Cards -->
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6 mb-8">
          <div class="restaurant-card p-6">
            <div class="flex items-center">
              <div class="w-12 h-12 bg-orange-100 dark:bg-orange-900/30 rounded-lg flex items-center justify-center">
                <Users class="w-6 h-6 text-orange-600 dark:text-orange-400" />
              </div>
              <div class="ml-4">
                <p class="text-sm font-medium text-gray-600 dark:text-gray-400">Total de Usuarios</p>
                <p class="text-2xl font-bold text-gray-900 dark:text-white">{{ userStats.total_users }}</p>
              </div>
            </div>
          </div>
          
          <div class="restaurant-card p-6">
            <div class="flex items-center">
              <div class="w-12 h-12 bg-green-100 dark:bg-green-900/30 rounded-lg flex items-center justify-center">
                <UserCheck class="w-6 h-6 text-green-600 dark:text-green-400" />
              </div>
              <div class="ml-4">
                <p class="text-sm font-medium text-gray-600 dark:text-gray-400">Usuarios Activos</p>
                <p class="text-2xl font-bold text-gray-900 dark:text-white">{{ userStats.active_users }}</p>
              </div>
            </div>
          </div>
          
          <div class="restaurant-card p-6">
            <div class="flex items-center">
              <div class="w-12 h-12 bg-red-100 dark:bg-red-900/30 rounded-lg flex items-center justify-center">
                <UserX class="w-6 h-6 text-red-600 dark:text-red-400" />
              </div>
              <div class="ml-4">
                <p class="text-sm font-medium text-gray-600 dark:text-gray-400">Usuarios Inactivos</p>
                <p class="text-2xl font-bold text-gray-900 dark:text-white">{{ userStats.inactive_users }}</p>
              </div>
            </div>
          </div>
          
          <div class="restaurant-card p-6">
            <div class="flex items-center">
              <div class="w-12 h-12 bg-blue-100 dark:bg-blue-900/30 rounded-lg flex items-center justify-center">
                <UserPlus class="w-6 h-6 text-blue-600 dark:text-blue-400" />
              </div>
              <div class="ml-4">
                <p class="text-sm font-medium text-gray-600 dark:text-gray-400">Nuevos este Mes</p>
                <p class="text-2xl font-bold text-gray-900 dark:text-white">{{ userStats.new_this_month }}</p>
              </div>
            </div>
          </div>
        </div>

        <!-- Filters and Controls -->
        <div class="bg-white dark:bg-gray-800 rounded-2xl shadow-lg border border-orange-200 dark:border-orange-800 p-6 mb-8">
          <div class="flex flex-col sm:flex-row items-center justify-between space-y-4 sm:space-y-0 sm:space-x-4">
            <!-- Search and Filters -->
            <div class="flex items-center space-x-4 flex-1">
              <div class="relative flex-1 max-w-md">
                <input
                  v-model="filters.search"
                  type="text"
                  placeholder="Buscar usuarios..."
                  class="input-restaurant w-full"
                />
                <Search class="absolute right-3 top-1/2 transform -translate-y-1/2 w-5 h-5 text-gray-400" />
              </div>
              
              <select
                v-model="filters.role"
                class="input-restaurant max-w-xs"
              >
                <option value="">Todos los roles</option>
                <option
                  v-for="role in roles"
                  :key="role.id"
                  :value="role.id"
                >
                  {{ role.nombre }}
                </option>
              </select>

              <select
                v-model="filters.status"
                class="input-restaurant max-w-xs"
              >
                <option value="">Todos los estados</option>
                <option value="active">Activos</option>
                <option value="inactive">Inactivos</option>
              </select>
            </div>

            <!-- Results Count -->
            <div class="flex items-center space-x-4">
              <span class="text-sm text-gray-600 dark:text-gray-400">
                {{ filteredUsers.length }} usuarios encontrados
              </span>
            </div>
          </div>
        </div>

        <!-- Users Table -->
        <div v-if="filteredUsers.length > 0">
          <div class="bg-white dark:bg-gray-800 rounded-2xl shadow-lg border border-orange-200 dark:border-orange-800 overflow-hidden">
            <div class="overflow-x-auto">
              <table class="min-w-full divide-y divide-orange-200 dark:divide-orange-800">
                <thead class="bg-orange-50 dark:bg-orange-900/20">
                  <tr>
                    <th class="px-6 py-4 text-left text-xs font-bold text-orange-800 dark:text-orange-200 uppercase tracking-wider">
                      Usuario
                    </th>
                    <th class="px-6 py-4 text-left text-xs font-bold text-orange-800 dark:text-orange-200 uppercase tracking-wider">
                      Rol
                    </th>
                    <th class="px-6 py-4 text-left text-xs font-bold text-orange-800 dark:text-orange-200 uppercase tracking-wider">
                      Estado
                    </th>
                    <th class="px-6 py-4 text-left text-xs font-bold text-orange-800 dark:text-orange-200 uppercase tracking-wider">
                      Último Acceso
                    </th>
                    <th class="px-6 py-4 text-left text-xs font-bold text-orange-800 dark:text-orange-200 uppercase tracking-wider">
                      Acciones
                    </th>
                  </tr>
                </thead>
                <tbody class="bg-white dark:bg-gray-800 divide-y divide-orange-100 dark:divide-orange-800">
                  <tr v-for="user in paginatedUsers" :key="user.id" class="hover:bg-orange-50 dark:hover:bg-orange-900/10 transition-colors">
                    <td class="px-6 py-4 whitespace-nowrap">
                      <div class="flex items-center">
                        <div class="w-10 h-10 bg-orange-100 dark:bg-orange-900/30 rounded-full flex items-center justify-center">
                          <span class="text-sm font-bold text-orange-800 dark:text-orange-200">
                            {{ getUserInitials(user.nombre) }}
                          </span>
                        </div>
                        <div class="ml-4">
                          <div class="text-sm font-bold text-gray-900 dark:text-white">
                            {{ user.nombre }}
                          </div>
                          <div class="text-sm text-gray-600 dark:text-gray-400">
                            {{ user.correo_electronico }}
                          </div>
                        </div>
                      </div>
                    </td>
                    <td class="px-6 py-4 whitespace-nowrap">
                      <span class="food-badge">
                        {{ user.rol?.nombre }}
                      </span>
                    </td>
                    <td class="px-6 py-4 whitespace-nowrap">
                      <span
                        :class="[
                          'inline-flex items-center px-3 py-1 rounded-full text-xs font-bold',
                          user.deleted_at 
                            ? 'bg-red-100 text-red-800 dark:bg-red-900/30 dark:text-red-200' 
                            : 'bg-green-100 text-green-800 dark:bg-green-900/30 dark:text-green-200'
                        ]"
                      >
                        {{ user.deleted_at ? 'Inactivo' : 'Activo' }}
                      </span>
                    </td>
                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-600 dark:text-gray-400">
                      {{ formatDate(user.last_login_at) }}
                    </td>
                    <td class="px-6 py-4 whitespace-nowrap text-sm font-medium">
                      <div class="flex items-center space-x-2">
                        <button
                          @click="editUser(user)"
                          class="btn-restaurant-secondary"
                        >
                          Editar
                        </button>
                        <button
                          @click="openDeleteModal(user)"
                          class="btn-restaurant bg-red-600 hover:bg-red-700"
                        >
                          Eliminar
                        </button>
                      </div>
                    </td>
                  </tr>
                </tbody>
              </table>
            </div>

            <!-- Pagination -->
            <div class="bg-orange-50 dark:bg-orange-900/20 px-6 py-4 flex items-center justify-between border-t border-orange-200 dark:border-orange-800">
              <div class="flex items-center space-x-2">
                <button
                  @click="currentPage = Math.max(1, currentPage - 1)"
                  :disabled="currentPage === 1"
                  class="btn-restaurant-secondary disabled:opacity-50 disabled:cursor-not-allowed"
                >
                  Anterior
                </button>
                
                <div class="flex items-center space-x-1">
                  <button
                    v-for="page in visiblePages"
                    :key="page"
                    @click="currentPage = page"
                    :class="[
                      'px-3 py-2 rounded-lg text-sm font-medium transition-colors',
                      page === currentPage
                        ? 'bg-orange-600 text-white'
                        : 'bg-gray-100 dark:bg-gray-700 text-gray-700 dark:text-gray-300 hover:bg-gray-200 dark:hover:bg-gray-600'
                    ]"
                  >
                    {{ page }}
                  </button>
                </div>
                
                <button
                  @click="currentPage = Math.min(totalPages, currentPage + 1)"
                  :disabled="currentPage === totalPages"
                  class="btn-restaurant-secondary disabled:opacity-50 disabled:cursor-not-allowed"
                >
                  Siguiente
                </button>
              </div>
            </div>
          </div>
        </div>

        <!-- Empty State -->
        <div
          v-else
          class="text-center py-12"
        >
          <div class="w-24 h-24 bg-orange-100 dark:bg-orange-900/30 rounded-full flex items-center justify-center mx-auto mb-6">
            <Users class="w-12 h-12 text-orange-400" />
          </div>
          <h3 class="text-xl font-semibold text-gray-900 dark:text-white mb-2">
            No se encontraron usuarios
          </h3>
          <p class="text-gray-600 dark:text-gray-400 mb-6">
            No hay usuarios que coincidan con tu búsqueda.
          </p>
          <button
            @click="showCreateModal = true"
            class="btn-restaurant inline-flex items-center"
          >
            <Plus class="w-5 h-5 mr-2" />
            Crear Primer Usuario
          </button>
        </div>
      </div>
    </div>

    <!-- Create/Edit Modal -->
    <div
      v-if="showCreateModal || showEditModal"
      class="fixed inset-0 bg-black/50 flex items-center justify-center z-50"
      @click="closeModal"
    >
      <div
        class="bg-white dark:bg-gray-800 rounded-2xl p-6 max-w-md w-full mx-4 shadow-2xl"
        @click.stop
      >
        <div class="flex items-center space-x-3 mb-4">
          <div class="w-12 h-12 bg-orange-100 dark:bg-orange-900/20 rounded-full flex items-center justify-center">
            <UserPlus v-if="!isEditing" class="w-6 h-6 text-orange-500" />
            <Edit v-else class="w-6 h-6 text-orange-500" />
          </div>
          <div>
            <h3 class="text-lg font-semibold text-gray-900 dark:text-white">
              {{ isEditing ? 'Editar Usuario' : 'Nuevo Usuario' }}
            </h3>
            <p class="text-sm text-gray-600 dark:text-gray-400">
              {{ isEditing ? 'Modifica la información del usuario' : 'Agrega un nuevo miembro al equipo' }}
            </p>
          </div>
        </div>
        
        <form @submit.prevent="saveUser" class="space-y-4">
          <div>
            <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">
              Nombre completo
            </label>
            <input
              v-model="userForm.nombre"
              type="text"
              placeholder="Ingresa el nombre completo"
              class="input-restaurant w-full"
              required
            />
          </div>
          
          <div>
            <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">
              Correo electrónico
            </label>
            <input
              v-model="userForm.correo_electronico"
              type="email"
              placeholder="usuario@ejemplo.com"
              class="input-restaurant w-full"
              required
            />
          </div>
          
          <div>
            <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">
              Teléfono
            </label>
            <input
              v-model="userForm.telefono"
              type="tel"
              placeholder="+1234567890"
              class="input-restaurant w-full"
            />
          </div>
          
          <div>
            <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">
              Rol
            </label>
            <select
              v-model="userForm.rol_id"
              class="input-restaurant w-full"
              required
            >
              <option value="">Selecciona un rol</option>
              <option v-for="role in roles" :key="role.id" :value="role.id">
                {{ role.nombre }}
              </option>
            </select>
          </div>
          
          <div v-if="!isEditing">
            <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">
              Contraseña
            </label>
            <input
              v-model="userForm.password"
              type="password"
              placeholder="Contraseña segura"
              class="input-restaurant w-full"
              required
            />
          </div>
          
          <div v-if="!isEditing">
            <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">
              Confirmar contraseña
            </label>
            <input
              v-model="userForm.password_confirmation"
              type="password"
              placeholder="Repite la contraseña"
              class="input-restaurant w-full"
              required
            />
          </div>
        </form>
        
        <div class="flex space-x-3 mt-6">
          <button
            @click="closeModal"
            class="btn-restaurant-secondary flex-1"
          >
            Cancelar
          </button>
          <button
            @click="saveUser"
            :disabled="saving"
            class="btn-restaurant flex-1 disabled:opacity-50"
          >
            <span v-if="!saving">{{ isEditing ? 'Actualizar' : 'Crear' }}</span>
            <span v-else>Cargando...</span>
          </button>
        </div>
      </div>
    </div>

    <!-- Delete Confirmation Modal -->
    <div
      v-if="showDeleteModal"
      class="fixed inset-0 bg-black/50 flex items-center justify-center z-50"
      @click="closeDeleteModal"
    >
      <div
        class="bg-white dark:bg-gray-800 rounded-2xl p-6 max-w-md w-full mx-4 shadow-2xl"
        @click.stop
      >
        <div class="flex items-center space-x-3 mb-4">
          <div class="w-12 h-12 bg-red-100 dark:bg-red-900/20 rounded-full flex items-center justify-center">
            <Trash class="w-6 h-6 text-red-500" />
          </div>
          <div>
            <h3 class="text-lg font-semibold text-gray-900 dark:text-white">
              Eliminar Usuario
            </h3>
            <p class="text-sm text-gray-600 dark:text-gray-400">
              Esta acción no se puede deshacer
            </p>
          </div>
        </div>
        
        <p class="text-gray-700 dark:text-gray-300 mb-6">
          ¿Estás seguro de que quieres eliminar <strong>{{ userToDelete?.nombre }}</strong>?
        </p>
        
        <div class="flex space-x-3">
          <button
            @click="closeDeleteModal"
            class="btn-restaurant-secondary flex-1"
          >
            Cancelar
          </button>
          <button
            @click="deleteUser"
            :disabled="deleting"
            class="btn-restaurant flex-1 bg-red-600 hover:bg-red-700 disabled:opacity-50"
          >
            <span v-if="!deleting">Eliminar</span>
            <span v-else>Cargando...</span>
          </button>
        </div>
      </div>
    </div>
  </AppLayout>
</template>

<script setup lang="ts">
import { ref, computed, onMounted, watch } from 'vue'
import { Head, router } from '@inertiajs/vue3'
import {
  Plus,
  Search,
  Users,
  UserCheck,
  UserX,
  UserPlus,
  Edit,
  Trash
} from 'lucide-vue-next'
// Definir tipos localmente para evitar conflictos
interface Usuario {
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

interface Role {
  id: number
  nombre: string
}
import AppLayout from '@/layouts/AppLayout.vue'
import type { BreadcrumbItem } from '@/types'

interface Props {
  users: Usuario[]
  roles: Role[]
  userStats: {
    total_users: number
    active_users: number
    inactive_users: number
    new_this_month: number
  }
}

const props = defineProps<Props>()

const filters = ref({
  search: '',
  role: '',
  status: ''
})

const currentPage = ref(1)
const itemsPerPage = 10
const showCreateModal = ref(false)
const showEditModal = ref(false)
const showDeleteModal = ref(false)
const isEditing = ref(false)
const saving = ref(false)
const deleting = ref(false)
const userToDelete = ref<Usuario | null>(null)

const userStats = ref(props.userStats || {
  total_users: 0,
  active_users: 0,
  inactive_users: 0,
  new_this_month: 0
})

const breadcrumbs: BreadcrumbItem[] = [
  {
    title: 'Dashboard',
    href: '/dashboard',
  },
  {
    title: 'Usuarios',
    href: '/usuarios',
  },
]

const userForm = ref({
  id: null as number | null,
  nombre: '',
  correo_electronico: '',
  telefono: '',
  rol_id: '',
  password: '',
  password_confirmation: ''
})

const filteredUsers = computed(() => {
  let filtered = props.users

  // Filter by search
  if (filters.value.search) {
    const searchLower = filters.value.search.toLowerCase()
    filtered = filtered.filter(user =>
      user.nombre.toLowerCase().includes(searchLower) ||
      user.correo_electronico.toLowerCase().includes(searchLower)
    )
  }

  // Filter by role
  if (filters.value.role) {
    filtered = filtered.filter(user => 
      user.rol_id === parseInt(filters.value.role)
    )
  }

  // Filter by status
  if (filters.value.status) {
    if (filters.value.status === 'active') {
      filtered = filtered.filter(user => !user.deleted_at)
    } else if (filters.value.status === 'inactive') {
      filtered = filtered.filter(user => user.deleted_at)
    }
  }

  return filtered
})

const totalPages = computed(() => Math.ceil(filteredUsers.value.length / itemsPerPage))

const paginatedUsers = computed(() => {
  const start = (currentPage.value - 1) * itemsPerPage
  const end = start + itemsPerPage
  return filteredUsers.value.slice(start, end)
})

const visiblePages = computed(() => {
  const pages = []
  const maxVisible = 5
  const start = Math.max(1, currentPage.value - Math.floor(maxVisible / 2))
  const end = Math.min(totalPages.value, start + maxVisible - 1)
  
  for (let i = start; i <= end; i++) {
    pages.push(i)
  }
  
  return pages
})

const getUserInitials = (name: string): string => {
  return name
    .split(' ')
    .map(word => word.charAt(0))
    .join('')
    .toUpperCase()
    .slice(0, 2)
}

const formatDate = (date: string | undefined): string => {
  if (!date) return 'Nunca'
  return new Date(date).toLocaleDateString('es-ES', {
    year: 'numeric',
    month: 'short',
    day: 'numeric',
    hour: '2-digit',
    minute: '2-digit'
  })
}

const editUser = (user: Usuario) => {
  isEditing.value = true
  userForm.value.id = user.id
  userForm.value.nombre = user.nombre
  userForm.value.correo_electronico = user.correo_electronico
  userForm.value.telefono = user.telefono || ''
  userForm.value.rol_id = user.rol_id.toString()
  userForm.value.password = ''
  userForm.value.password_confirmation = ''
  showEditModal.value = true
}

const openDeleteModal = (user: Usuario) => {
  userToDelete.value = user
  showDeleteModal.value = true
}

const closeModal = () => {
  showCreateModal.value = false
  showEditModal.value = false
  isEditing.value = false
  resetForm()
}

const closeDeleteModal = () => {
  showDeleteModal.value = false
  userToDelete.value = null
}

const resetForm = () => {
  userForm.value.id = null
  userForm.value.nombre = ''
  userForm.value.correo_electronico = ''
  userForm.value.telefono = ''
  userForm.value.rol_id = ''
  userForm.value.password = ''
  userForm.value.password_confirmation = ''
}

const saveUser = async () => {
  // Validación del lado del cliente
  if (!userForm.value.nombre.trim()) {
    alert('El nombre es requerido')
    return
  }
  
  if (!userForm.value.correo_electronico.trim()) {
    alert('El correo electrónico es requerido')
    return
  }
  
  if (!userForm.value.rol_id) {
    alert('Debe seleccionar un rol')
    return
  }
  
  if (!isEditing.value && (!userForm.value.password || userForm.value.password.length < 8)) {
    alert('La contraseña debe tener al menos 8 caracteres')
    return
  }
  
  if (!isEditing.value && userForm.value.password !== userForm.value.password_confirmation) {
    alert('Las contraseñas no coinciden')
    return
  }
  
  saving.value = true
  
  try {
    if (isEditing.value) {
      await router.put(`/usuarios/${userForm.value.id}`, userForm.value)
    } else {
      await router.post('/usuarios', userForm.value)
    }
    
    closeModal()
  } catch (error: any) {
    console.error('Error saving user:', error)
    alert('Error al guardar usuario: ' + (error.response?.data?.message || error.message))
  } finally {
    saving.value = false
  }
}

const deleteUser = async () => {
  if (!userToDelete.value) return
  
  deleting.value = true
  
  try {
    await router.delete(`/usuarios/${userToDelete.value.id}`)
    closeDeleteModal()
  } catch (error) {
    console.error('Error deleting user:', error)
  } finally {
    deleting.value = false
  }
}

// Reset to first page when filters change
watch([filters], () => {
  currentPage.value = 1
})

onMounted(() => {
  // Any initialization if needed
})
</script> 