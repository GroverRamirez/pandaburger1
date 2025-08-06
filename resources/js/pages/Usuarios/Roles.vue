<template>
  <AppLayout :breadcrumbs="breadcrumbs">
    <Head title="Roles - Pandaburger" />
    
    <div class="min-h-screen bg-gradient-to-br from-orange-50 to-orange-100 dark:from-gray-900 dark:to-orange-900/10">
      <!-- Header Section -->
      <div class="bg-white dark:bg-gray-800 shadow-lg border-b border-orange-200 dark:border-orange-800">
        <div class="max-w-7xl mx-auto px-6 py-8">
          <div class="flex items-center justify-between">
            <div>
              <h1 class="text-4xl font-bold text-gradient mb-2">
                🛡️ Roles
              </h1>
              <p class="text-lg text-gray-600 dark:text-gray-400">
                Administra roles y permisos del sistema
              </p>
            </div>
            <button
              @click="showCreateModal = true"
              class="btn-restaurant inline-flex items-center"
            >
              <Plus class="w-5 h-5 mr-2" />
              Nuevo Rol
            </button>
          </div>
        </div>
      </div>

      <!-- Content Section -->
      <div class="max-w-7xl mx-auto px-6 py-8">
        <!-- Roles Grid -->
        <div v-if="roles.length > 0">
          <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
            <div
              v-for="role in roles"
              :key="role.id"
              class="restaurant-card p-6"
            >
              <div class="flex items-center justify-between mb-4">
                <div class="flex items-center space-x-3">
                  <div class="w-12 h-12 bg-gradient-to-r from-blue-500 to-purple-600 rounded-lg flex items-center justify-center">
                    <Shield class="h-6 w-6 text-white" />
                  </div>
                  <div>
                    <h3 class="text-lg font-semibold text-gray-900 dark:text-white">
                      {{ role.nombre }}
                    </h3>
                    <p class="text-sm text-gray-600 dark:text-gray-400">
                      {{ role.usuarios_count || 0 }} usuarios
                    </p>
                  </div>
                </div>
                                 <div class="relative">
                   <button 
                     @click="toggleDropdown(role.id)"
                     class="btn-restaurant-secondary p-2"
                   >
                     <MoreVertical class="h-4 w-4" />
                   </button>
                   
                   <!-- Dropdown Menu -->
                   <div 
                     v-if="openDropdown === role.id"
                     class="absolute right-0 top-full mt-1 w-48 bg-white dark:bg-gray-800 rounded-lg shadow-lg border border-orange-200 dark:border-orange-800 z-10"
                   >
                     <div class="py-1">
                       <button
                         @click="editRole(role)"
                         class="w-full px-4 py-2 text-left text-sm text-gray-700 dark:text-gray-300 hover:bg-orange-50 dark:hover:bg-orange-900/20 flex items-center"
                       >
                         <Edit class="h-4 w-4 mr-2" />
                         Editar
                       </button>
                       <button
                         @click="openPermissionsModal(role)"
                         class="w-full px-4 py-2 text-left text-sm text-gray-700 dark:text-gray-300 hover:bg-orange-50 dark:hover:bg-orange-900/20 flex items-center"
                       >
                         <Shield class="h-4 w-4 mr-2" />
                         Permisos
                       </button>
                       <div class="border-t border-orange-200 dark:border-orange-800 my-1"></div>
                       <button
                         @click="openDeleteModal(role)"
                         class="w-full px-4 py-2 text-left text-sm text-red-600 hover:bg-red-50 dark:hover:bg-red-900/20 flex items-center"
                       >
                         <Trash class="h-4 w-4 mr-2" />
                         Eliminar
                       </button>
                     </div>
                   </div>
                 </div>
              </div>

              <div class="space-y-3">
                <div v-if="role.descripcion" class="text-sm text-gray-600 dark:text-gray-400">
                  {{ role.descripcion }}
                </div>
                
                <div class="flex items-center justify-between text-sm">
                  <span class="text-gray-500 dark:text-gray-400">Creado:</span>
                  <span class="text-gray-900 dark:text-white">
                    {{ formatDate(role.created_at) }}
                  </span>
                </div>

                <div class="flex items-center justify-between text-sm">
                  <span class="text-gray-500 dark:text-gray-400">Estado:</span>
                  <span
                    :class="[
                      'inline-flex items-center px-2 py-1 rounded-full text-xs font-medium',
                      role.deleted_at ? 'bg-red-100 text-red-800 dark:bg-red-900/30 dark:text-red-200' : 'bg-green-100 text-green-800 dark:bg-green-900/30 dark:text-green-200'
                    ]"
                  >
                    {{ role.deleted_at ? 'Inactivo' : 'Activo' }}
                  </span>
                </div>
              </div>

              <!-- Permissions Preview -->
              <div v-if="role.permisos && role.permisos.length > 0" class="mt-4 pt-4 border-t border-orange-200 dark:border-orange-800">
                <h4 class="text-sm font-medium text-gray-900 dark:text-white mb-2">Permisos:</h4>
                <div class="flex flex-wrap gap-1">
                  <span
                    v-for="permiso in role.permisos.slice(0, 3)"
                    :key="permiso.id"
                    class="food-badge"
                  >
                    {{ permiso.nombre }}
                  </span>
                  <span
                    v-if="role.permisos.length > 3"
                    class="inline-flex items-center px-2 py-1 rounded-full text-xs font-medium bg-gray-100 text-gray-800 dark:bg-gray-900/30 dark:text-gray-200"
                  >
                    +{{ role.permisos.length - 3 }} más
                  </span>
                </div>
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
            <Shield class="w-12 h-12 text-orange-400" />
          </div>
          <h3 class="text-xl font-semibold text-gray-900 dark:text-white mb-2">
            No se encontraron roles
          </h3>
          <p class="text-gray-600 dark:text-gray-400 mb-6">
            No hay roles configurados en el sistema.
          </p>
          <button
            @click="showCreateModal = true"
            class="btn-restaurant inline-flex items-center"
          >
            <Plus class="w-5 h-5 mr-2" />
            Crear Primer Rol
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
            <Shield v-if="!isEditing" class="w-6 h-6 text-orange-500" />
            <Edit v-else class="w-6 h-6 text-orange-500" />
          </div>
          <div>
            <h3 class="text-lg font-semibold text-gray-900 dark:text-white">
              {{ isEditing ? 'Editar Rol' : 'Nuevo Rol' }}
            </h3>
            <p class="text-sm text-gray-600 dark:text-gray-400">
              {{ isEditing ? 'Modifica la información del rol' : 'Crea un nuevo rol en el sistema' }}
            </p>
          </div>
        </div>
        
        <form @submit.prevent="saveRole" class="space-y-4">
          <div>
            <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">
              Nombre del rol
            </label>
            <input
              v-model="roleForm.nombre"
              type="text"
              placeholder="Ej: Administrador, Usuario, etc."
              class="input-restaurant w-full"
              required
            />
          </div>
          
          <div>
            <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">
              Descripción
            </label>
            <textarea
              v-model="roleForm.descripcion"
              rows="3"
              class="input-restaurant w-full"
              placeholder="Describe las responsabilidades de este rol..."
            ></textarea>
          </div>
          
          <div>
            <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">
              Permisos
            </label>
            <div class="mt-2 space-y-2 max-h-48 overflow-y-auto">
              <div
                v-for="permiso in permisos"
                :key="permiso.id"
                class="flex items-center space-x-3"
              >
                <input
                  type="checkbox"
                  :id="`permiso-${permiso.id}`"
                  :value="permiso.id"
                  v-model="roleForm.permisos"
                  class="rounded border-gray-300 text-orange-600 focus:ring-orange-500"
                />
                <label :for="`permiso-${permiso.id}`" class="text-sm">
                  {{ permiso.nombre }}
                  <span class="text-gray-500 dark:text-gray-400 text-xs block">
                    {{ permiso.descripcion }}
                  </span>
                </label>
              </div>
            </div>
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
            @click="saveRole"
            :disabled="saving"
            class="btn-restaurant flex-1 disabled:opacity-50"
          >
            <span v-if="!saving">{{ isEditing ? 'Actualizar' : 'Crear' }}</span>
            <span v-else>Cargando...</span>
          </button>
        </div>
      </div>
    </div>

    <!-- Permissions Modal -->
    <div
      v-if="showPermissionsModal"
      class="fixed inset-0 bg-black/50 flex items-center justify-center z-50"
      @click="closePermissionsModal"
    >
      <div
        class="bg-white dark:bg-gray-800 rounded-2xl p-6 max-w-lg w-full mx-4 shadow-2xl"
        @click.stop
      >
        <div class="flex items-center space-x-3 mb-4">
          <div class="w-12 h-12 bg-blue-100 dark:bg-blue-900/20 rounded-full flex items-center justify-center">
            <Shield class="w-6 h-6 text-blue-500" />
          </div>
          <div>
            <h3 class="text-lg font-semibold text-gray-900 dark:text-white">
              Permisos del Rol: {{ selectedRole?.nombre }}
            </h3>
            <p class="text-sm text-gray-600 dark:text-gray-400">
              Gestiona los permisos asignados a este rol
            </p>
          </div>
        </div>
        
        <div class="space-y-4">
          <div class="grid grid-cols-1 gap-4">
            <div
              v-for="permiso in permisos"
              :key="permiso.id"
              class="flex items-center space-x-3 p-3 rounded-lg border border-orange-200 dark:border-orange-800"
            >
              <input
                type="checkbox"
                :id="`modal-permiso-${permiso.id}`"
                :value="permiso.id"
                v-model="selectedRolePermisos"
                class="rounded border-gray-300 text-orange-600 focus:ring-orange-500"
              />
              <div class="flex-1">
                <label :for="`modal-permiso-${permiso.id}`" class="font-medium text-gray-900 dark:text-white">
                  {{ permiso.nombre }}
                </label>
                <p class="text-sm text-gray-500 dark:text-gray-400">
                  {{ permiso.descripcion }}
                </p>
              </div>
            </div>
          </div>
        </div>
        
        <div class="flex space-x-3 mt-6">
          <button
            @click="closePermissionsModal"
            class="btn-restaurant-secondary flex-1"
          >
            Cancelar
          </button>
          <button
            @click="savePermissions"
            :disabled="savingPermissions"
            class="btn-restaurant flex-1 bg-blue-600 hover:bg-blue-700 disabled:opacity-50"
          >
            <span v-if="!savingPermissions">Guardar Permisos</span>
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
              Eliminar Rol
            </h3>
            <p class="text-sm text-gray-600 dark:text-gray-400">
              Esta acción no se puede deshacer
            </p>
          </div>
        </div>
        
        <p class="text-gray-700 dark:text-gray-300 mb-6">
          ¿Estás seguro de que quieres eliminar <strong>{{ roleToDelete?.nombre }}</strong>?
        </p>
        
        <div class="flex space-x-3">
          <button
            @click="closeDeleteModal"
            class="btn-restaurant-secondary flex-1"
          >
            Cancelar
          </button>
          <button
            @click="deleteRole"
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
import { ref, reactive, onMounted } from 'vue'
import { Head, router } from '@inertiajs/vue3'
import {
  Plus,
  Shield,
  Edit,
  Trash,
  MoreVertical
} from 'lucide-vue-next'

import AppLayout from '@/layouts/AppLayout.vue'
import type { BreadcrumbItem } from '@/types'

// Definir tipos localmente
interface Role {
  id: number
  nombre: string
  descripcion?: string
  usuarios_count?: number
  permisos?: Permiso[]
  created_at: string
  deleted_at?: string
}

interface Permiso {
  id: number
  nombre: string
  descripcion: string
}

interface Props {
  roles: Role[]
  permisos: Permiso[]
}

const props = defineProps<Props>()

const roles = ref<Role[]>(props.roles || [])
const permisos = ref<Permiso[]>(props.permisos || [])
const selectedRole = ref<Role | null>(null)
const selectedRolePermisos = ref<number[]>([])

const showCreateModal = ref(false)
const showEditModal = ref(false)
const showPermissionsModal = ref(false)
const showDeleteModal = ref(false)
const isEditing = ref(false)
const saving = ref(false)
const savingPermissions = ref(false)
const deleting = ref(false)
const roleToDelete = ref<Role | null>(null)
const openDropdown = ref<number | null>(null)

const breadcrumbs: BreadcrumbItem[] = [
  {
    title: 'Dashboard',
    href: '/dashboard',
  },
  {
    title: 'Roles',
    href: '/roles',
  },
]

const roleForm = reactive({
  id: null as number | null,
  nombre: '',
  descripcion: '',
  permisos: [] as number[]
})

const editRole = (role: Role) => {
  isEditing.value = true
  roleForm.id = role.id
  roleForm.nombre = role.nombre
  roleForm.descripcion = role.descripcion || ''
  roleForm.permisos = role.permisos?.map(p => p.id) || []
  showEditModal.value = true
}

const openPermissionsModal = (role: Role) => {
  selectedRole.value = role
  selectedRolePermisos.value = role.permisos?.map(p => p.id) || []
  showPermissionsModal.value = true
}

const openDeleteModal = (role: Role) => {
  roleToDelete.value = role
  showDeleteModal.value = true
}

const toggleDropdown = (roleId: number) => {
  openDropdown.value = openDropdown.value === roleId ? null : roleId
}

const closeModal = () => {
  showCreateModal.value = false
  showEditModal.value = false
  isEditing.value = false
  resetForm()
}

const closePermissionsModal = () => {
  showPermissionsModal.value = false
  selectedRole.value = null
  selectedRolePermisos.value = []
}

const closeDeleteModal = () => {
  showDeleteModal.value = false
  roleToDelete.value = null
}

const resetForm = () => {
  roleForm.id = null
  roleForm.nombre = ''
  roleForm.descripcion = ''
  roleForm.permisos = []
}

const saveRole = async () => {
  saving.value = true
  
  try {
    if (isEditing.value) {
      await router.put(`/roles/${roleForm.id}`, roleForm)
    } else {
      await router.post('/roles', roleForm)
    }
    
    closeModal()
  } catch (error) {
    console.error('Error saving role:', error)
  } finally {
    saving.value = false
  }
}

const savePermissions = async () => {
  if (!selectedRole.value) return
  
  savingPermissions.value = true
  
  try {
    await router.put(`/roles/${selectedRole.value.id}/permisos`, {
      permisos: selectedRolePermisos.value
    })
    
    closePermissionsModal()
  } catch (error) {
    console.error('Error saving permissions:', error)
  } finally {
    savingPermissions.value = false
  }
}

const deleteRole = async () => {
  if (!roleToDelete.value) return
  
  deleting.value = true
  
  try {
    await router.delete(`/roles/${roleToDelete.value.id}`)
    closeDeleteModal()
  } catch (error) {
    console.error('Error deleting role:', error)
  } finally {
    deleting.value = false
  }
}

const formatDate = (date: string) => {
  return new Date(date).toLocaleDateString('es-ES', {
    year: 'numeric',
    month: 'short',
    day: 'numeric'
  })
}

onMounted(() => {
  // Cerrar dropdown cuando se hace clic fuera
  document.addEventListener('click', (event) => {
    const target = event.target as HTMLElement
    if (!target.closest('.relative')) {
      openDropdown.value = null
    }
  })
})
</script> 