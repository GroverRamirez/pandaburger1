<template>
  <AppLayout :breadcrumbs="breadcrumbs">
    <Head title="Mi Perfil - Pandaburger" />
    
    <div class="min-h-screen bg-gradient-to-br from-orange-50 to-orange-100 dark:from-gray-900 dark:to-orange-900/10">
      <!-- Header Section -->
      <div class="bg-white dark:bg-gray-800 shadow-lg border-b border-orange-200 dark:border-orange-800">
        <div class="max-w-7xl mx-auto px-6 py-8">
          <div class="flex items-center justify-between">
            <div>
              <h1 class="text-4xl font-bold text-gradient mb-2">
                👤 Mi Perfil
              </h1>
              <p class="text-lg text-gray-600 dark:text-gray-400">
                Gestiona tu información personal y configuración de cuenta
              </p>
            </div>
          </div>
        </div>
      </div>

             <!-- Content Section -->
       <div class="max-w-7xl mx-auto px-6 py-8">
         <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
             <!-- Profile Card -->
       <div class="lg:col-span-1">
         <div class="restaurant-card p-6">
          <div class="text-center">
            <div class="relative inline-block">
              <Avatar class="h-24 w-24">
                <AvatarImage :src="user.avatar || ''" />
                <AvatarFallback class="text-2xl">
                  {{ getUserInitials(user.nombre) }}
                </AvatarFallback>
              </Avatar>
                             <button
                 @click="showAvatarModal = true"
                 class="btn-restaurant-secondary absolute -bottom-2 -right-2 h-8 w-8 rounded-full p-0"
               >
                 <Icon name="camera" class="h-4 w-4" />
               </button>
            </div>
            
            <h2 class="mt-4 text-xl font-semibold text-gray-900 dark:text-white">
              {{ user.nombre }}
            </h2>
            <p class="text-sm text-gray-500 dark:text-gray-400">
              {{ user.correo_electronico }}
            </p>
            
            <div class="mt-4 flex items-center justify-center space-x-2">
              <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-blue-100 text-blue-800 dark:bg-blue-900 dark:text-blue-200">
                {{ user.rol?.nombre }}
              </span>
            </div>
          </div>

          <div class="mt-6 space-y-4">
            <div class="flex items-center space-x-3">
              <Icon name="calendar" class="h-4 w-4 text-gray-400" />
              <div>
                <p class="text-sm font-medium text-gray-900 dark:text-white">Miembro desde</p>
                <p class="text-sm text-gray-500 dark:text-gray-400">
                  {{ formatDate(user.created_at) }}
                </p>
              </div>
            </div>

            <div class="flex items-center space-x-3">
              <Icon name="clock" class="h-4 w-4 text-gray-400" />
              <div>
                <p class="text-sm font-medium text-gray-900 dark:text-white">Último acceso</p>
                <p class="text-sm text-gray-500 dark:text-gray-400">
                  {{ formatDate(user.last_login_at) }}
                </p>
              </div>
            </div>

            <div v-if="user.telefono" class="flex items-center space-x-3">
              <Icon name="phone" class="h-4 w-4 text-gray-400" />
              <div>
                <p class="text-sm font-medium text-gray-900 dark:text-white">Teléfono</p>
                <p class="text-sm text-gray-500 dark:text-gray-400">
                  {{ user.telefono }}
                </p>
              </div>
            </div>
          </div>
        </div>
      </div>

      <!-- Main Content -->
      <div class="lg:col-span-2 space-y-6">
                 <!-- Personal Information -->
         <div class="restaurant-card">
           <div class="px-6 py-4 border-b border-orange-200 dark:border-orange-800">
            <h3 class="text-lg font-medium text-gray-900 dark:text-white">
              Información Personal
            </h3>
            <p class="text-sm text-gray-500 dark:text-gray-400">
              Actualiza tu información personal
            </p>
          </div>
          
          <div class="p-6">
            <form @submit.prevent="updateProfile" class="space-y-4">
              <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                <div>
                  <Label for="nombre">Nombre completo</Label>
                                     <input
                     id="nombre"
                     v-model="profileForm.nombre"
                     placeholder="Tu nombre completo"
                     required
                     class="input-restaurant w-full"
                   />
                </div>
                <div>
                  <Label for="correo_electronico">Correo electrónico</Label>
                                     <input
                     id="correo_electronico"
                     v-model="profileForm.correo_electronico"
                     type="email"
                     placeholder="tu@email.com"
                     required
                     class="input-restaurant w-full"
                   />
                </div>
              </div>

              <div>
                <Label for="telefono">Teléfono</Label>
                                 <input
                   id="telefono"
                   v-model="profileForm.telefono"
                   placeholder="+1234567890"
                   class="input-restaurant w-full"
                 />
              </div>

                             <div class="flex justify-end">
                 <button 
                   type="submit" 
                   :disabled="savingProfile"
                   class="btn-restaurant disabled:opacity-50"
                 >
                   <span v-if="!savingProfile">Guardar Cambios</span>
                   <span v-else>Cargando...</span>
                 </button>
               </div>
            </form>
          </div>
        </div>

                 <!-- Change Password -->
         <div class="restaurant-card">
           <div class="px-6 py-4 border-b border-orange-200 dark:border-orange-800">
            <h3 class="text-lg font-medium text-gray-900 dark:text-white">
              Cambiar Contraseña
            </h3>
            <p class="text-sm text-gray-500 dark:text-gray-400">
              Actualiza tu contraseña de acceso
            </p>
          </div>
          
          <div class="p-6">
            <form @submit.prevent="changePassword" class="space-y-4">
              <div>
                <Label for="current_password">Contraseña actual</Label>
                                 <input
                   id="current_password"
                   v-model="passwordForm.current_password"
                   type="password"
                   placeholder="Tu contraseña actual"
                   required
                   class="input-restaurant w-full"
                 />
              </div>

              <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                <div>
                  <Label for="new_password">Nueva contraseña</Label>
                                     <input
                     id="new_password"
                     v-model="passwordForm.new_password"
                     type="password"
                     placeholder="Nueva contraseña"
                     required
                     class="input-restaurant w-full"
                   />
                </div>
                <div>
                  <Label for="new_password_confirmation">Confirmar nueva contraseña</Label>
                                     <input
                     id="new_password_confirmation"
                     v-model="passwordForm.new_password_confirmation"
                     type="password"
                     placeholder="Confirma la nueva contraseña"
                     required
                     class="input-restaurant w-full"
                   />
                </div>
              </div>

                             <div class="flex justify-end">
                 <button 
                   type="submit" 
                   :disabled="savingPassword"
                   class="btn-restaurant disabled:opacity-50"
                 >
                   <span v-if="!savingPassword">Cambiar Contraseña</span>
                   <span v-else>Cargando...</span>
                 </button>
               </div>
            </form>
          </div>
        </div>

                 <!-- Security Settings -->
         <div class="restaurant-card">
           <div class="px-6 py-4 border-b border-orange-200 dark:border-orange-800">
            <h3 class="text-lg font-medium text-gray-900 dark:text-white">
              Configuración de Seguridad
            </h3>
            <p class="text-sm text-gray-500 dark:text-gray-400">
              Gestiona la seguridad de tu cuenta
            </p>
          </div>
          
          <div class="p-6 space-y-4">
            <div class="flex items-center justify-between">
              <div>
                <h4 class="text-sm font-medium text-gray-900 dark:text-white">
                  Autenticación de dos factores
                </h4>
                <p class="text-sm text-gray-500 dark:text-gray-400">
                  Añade una capa extra de seguridad a tu cuenta
                </p>
              </div>
              <button class="btn-restaurant-secondary text-sm px-3 py-1">
                Configurar
              </button>
            </div>

            <div class="flex items-center justify-between">
              <div>
                <h4 class="text-sm font-medium text-gray-900 dark:text-white">
                  Sesiones activas
                </h4>
                <p class="text-sm text-gray-500 dark:text-gray-400">
                  Gestiona las sesiones activas en otros dispositivos
                </p>
              </div>
              <button class="btn-restaurant-secondary text-sm px-3 py-1">
                Ver sesiones
              </button>
            </div>

            <div class="flex items-center justify-between">
              <div>
                <h4 class="text-sm font-medium text-gray-900 dark:text-white">
                  Notificaciones de seguridad
                </h4>
                <p class="text-sm text-gray-500 dark:text-gray-400">
                  Recibe alertas sobre actividad sospechosa
                </p>
              </div>
              <button class="btn-restaurant-secondary text-sm px-3 py-1">
                Configurar
              </button>
            </div>
          </div>
        </div>
      </div>
    </div>

    <!-- Avatar Upload Modal -->
    <Dialog :open="showAvatarModal" @close="closeAvatarModal">
      <DialogContent class="sm:max-w-[400px]">
        <DialogHeader>
          <DialogTitle>Cambiar Foto de Perfil</DialogTitle>
          <DialogDescription>
            Sube una nueva imagen para tu perfil
          </DialogDescription>
        </DialogHeader>

        <div class="space-y-4">
          <div class="flex justify-center">
            <div class="relative">
              <Avatar class="h-32 w-32">
                <AvatarImage :src="avatarPreview || user.avatar || ''" />
                <AvatarFallback class="text-4xl">
                  {{ getUserInitials(user.nombre) }}
                </AvatarFallback>
              </Avatar>
            </div>
          </div>

          <div>
            <Label for="avatar">Seleccionar imagen</Label>
                         <input
               id="avatar"
               type="file"
               accept="image/*"
               @change="handleAvatarChange"
               class="input-restaurant w-full mt-1"
             />
          </div>
        </div>

                 <DialogFooter>
           <button 
             @click="closeAvatarModal"
             class="btn-restaurant-secondary"
           >
             Cancelar
           </button>
           <button 
             @click="uploadAvatar" 
             :disabled="uploadingAvatar"
             class="btn-restaurant disabled:opacity-50"
           >
             <span v-if="!uploadingAvatar">Subir Imagen</span>
             <span v-else>Cargando...</span>
           </button>
         </DialogFooter>
      </DialogContent>
    </Dialog>
      </div>
    </div>
  </AppLayout>
</template>

<script setup lang="ts">
import { ref, reactive, onMounted } from 'vue'
import { Head, router } from '@inertiajs/vue3'
import { Avatar, AvatarImage, AvatarFallback } from '@/components/ui/avatar'
import { Dialog, DialogContent, DialogDescription, DialogFooter, DialogHeader, DialogTitle } from '@/components/ui/dialog'
import { Label } from '@/components/ui/label'
import Icon from '@/components/Icon.vue'
import AppLayout from '@/layouts/AppLayout.vue'
import type { BreadcrumbItem } from '@/types'

interface User {
  id: number
  nombre: string
  correo_electronico: string
  telefono?: string
  rol?: {
    id: number
    nombre: string
  }
  created_at: string
  last_login_at?: string
  avatar?: string
}

interface Props {
  user: User
}

const props = defineProps<Props>()

const user = ref<User>(props.user)

const breadcrumbs: BreadcrumbItem[] = [
  {
    title: 'Dashboard',
    href: '/dashboard',
  },
  {
    title: 'Mi Perfil',
    href: '/profile',
  },
]

const showAvatarModal = ref(false)
const avatarPreview = ref<string | null>(null)
const uploadingAvatar = ref(false)
const savingProfile = ref(false)
const savingPassword = ref(false)

const profileForm = reactive({
  nombre: '',
  correo_electronico: '',
  telefono: ''
})

const passwordForm = reactive({
  current_password: '',
  new_password: '',
  new_password_confirmation: ''
})

onMounted(() => {
  // Inicializar el formulario con los datos del usuario
  profileForm.nombre = user.value.nombre
  profileForm.correo_electronico = user.value.correo_electronico
  profileForm.telefono = user.value.telefono || ''
})

const updateProfile = async () => {
  savingProfile.value = true
  
  try {
    await router.put('/api/profile', profileForm)
    // Recargar la página para obtener los datos actualizados
    window.location.reload()
  } catch (error) {
    console.error('Error updating profile:', error)
  } finally {
    savingProfile.value = false
  }
}

const changePassword = async () => {
  savingPassword.value = true
  
  try {
    await router.put('/api/profile/password', passwordForm)
    
    // Reset password form
    passwordForm.current_password = ''
    passwordForm.new_password = ''
    passwordForm.new_password_confirmation = ''
  } catch (error) {
    console.error('Error changing password:', error)
  } finally {
    savingPassword.value = false
  }
}

const handleAvatarChange = (event: Event) => {
  const target = event.target as HTMLInputElement
  const file = target.files?.[0]
  
  if (file) {
    const reader = new FileReader()
    reader.onload = (e) => {
      avatarPreview.value = e.target?.result as string
    }
    reader.readAsDataURL(file)
  }
}

const uploadAvatar = async () => {
  if (!avatarPreview.value) return
  
  uploadingAvatar.value = true
  
  try {
    // Here you would typically upload the file to your server
    // For now, we'll just simulate the upload
    await new Promise(resolve => setTimeout(resolve, 1000))
    
    closeAvatarModal()
    // Recargar la página para obtener los datos actualizados
    window.location.reload()
  } catch (error) {
    console.error('Error uploading avatar:', error)
  } finally {
    uploadingAvatar.value = false
  }
}

const closeAvatarModal = () => {
  showAvatarModal.value = false
  avatarPreview.value = null
}

const getUserInitials = (name: string) => {
  return name
    .split(' ')
    .map(word => word.charAt(0))
    .join('')
    .toUpperCase()
    .slice(0, 2)
}

const formatDate = (date: string | undefined) => {
  if (!date) return 'Nunca'
  return new Date(date).toLocaleDateString('es-ES', {
    year: 'numeric',
    month: 'long',
    day: 'numeric',
    hour: '2-digit',
    minute: '2-digit'
  })
}
</script> 