<script setup lang="ts">
import { reactive, ref, computed, onMounted } from 'vue';
import { Card, CardContent, CardHeader, CardTitle } from '@/components/ui/card';
import { Input } from '@/components/ui/input';
import { Label } from '@/components/ui/label';
import { Button } from '@/components/ui/button';
import { Badge } from '@/components/ui/badge';
import { Link, usePage } from '@inertiajs/vue3';
import { 
  ArrowLeft, 
  User, 
  Mail, 
  Phone, 
  MapPin, 
  Lock, 
  Eye, 
  EyeOff,
  CheckCircle,
  AlertCircle,
  Save,
  UserPlus
} from 'lucide-vue-next';

interface ClienteForm {
  nombre: string;
  correo_electronico: string;
  telefono: string;
  direccion: string;
  password_hash: string;
}

interface ValidationErrors {
  nombre?: string;
  correo_electronico?: string;
  telefono?: string;
  direccion?: string;
  password_hash?: string;
}

const props = defineProps<{ 
    cliente?: ClienteForm, 
    isEdit?: boolean 
}>();

const emit = defineEmits(['submit']);

const form = reactive<ClienteForm>({
  nombre: props.cliente?.nombre || '',
  correo_electronico: props.cliente?.correo_electronico || '',
  telefono: props.cliente?.telefono || '',
  direccion: props.cliente?.direccion || '',
  password_hash: '',
});

const errors = ref<ValidationErrors>({});
const isLoading = ref(false);
const showPassword = ref(false);
const isFormValid = ref(false);

const page = usePage();

// Validación inicial cuando se carga el formulario
const validateForm = () => {
  const newErrors: ValidationErrors = {};
  
  if (!form.nombre.trim()) {
    newErrors.nombre = 'El nombre es requerido';
  } else if (form.nombre.length < 2) {
    newErrors.nombre = 'El nombre debe tener al menos 2 caracteres';
  }
  
  if (form.correo_electronico && !isValidEmail(form.correo_electronico)) {
    newErrors.correo_electronico = 'El email no es válido';
  }
  
  if (form.telefono && !isValidPhone(form.telefono)) {
    newErrors.telefono = 'El teléfono no es válido';
  }
  
  // Para edición, la contraseña es opcional
  if (!props.isEdit && !form.password_hash) {
    newErrors.password_hash = 'La contraseña es requerida para nuevos clientes';
  } else if (form.password_hash && form.password_hash.length < 6) {
    newErrors.password_hash = 'La contraseña debe tener al menos 6 caracteres';
  }
  
  errors.value = newErrors;
  isFormValid.value = Object.keys(newErrors).length === 0;
  return isFormValid.value;
};

// Validación en tiempo real para campos individuales
const validateField = (field: keyof ClienteForm) => {
  const newErrors = { ...errors.value };
  
  switch (field) {
    case 'nombre':
      if (!form.nombre.trim()) {
        newErrors.nombre = 'El nombre es requerido';
      } else if (form.nombre.length < 2) {
        newErrors.nombre = 'El nombre debe tener al menos 2 caracteres';
      } else {
        delete newErrors.nombre;
      }
      break;
      
    case 'correo_electronico':
      if (form.correo_electronico && !isValidEmail(form.correo_electronico)) {
        newErrors.correo_electronico = 'El email no es válido';
      } else {
        delete newErrors.correo_electronico;
      }
      break;
      
    case 'telefono':
      if (form.telefono && !isValidPhone(form.telefono)) {
        newErrors.telefono = 'El teléfono no es válido';
      } else {
        delete newErrors.telefono;
      }
      break;
      
    case 'password_hash':
      if (!props.isEdit && !form.password_hash) {
        newErrors.password_hash = 'La contraseña es requerida para nuevos clientes';
      } else if (form.password_hash && form.password_hash.length < 6) {
        newErrors.password_hash = 'La contraseña debe tener al menos 6 caracteres';
      } else {
        delete newErrors.password_hash;
      }
      break;
  }
  
  errors.value = newErrors;
  isFormValid.value = Object.keys(newErrors).length === 0;
};

const isValidEmail = (email: string): boolean => {
  const emailRegex = /^[^\s@]+@[^\s@]+\.[^\s@]+$/;
  return emailRegex.test(email);
};

const isValidPhone = (phone: string): boolean => {
  const phoneRegex = /^[\+]?[0-9\s\-\(\)]{7,}$/;
  return phoneRegex.test(phone);
};

const handleInput = (field: keyof ClienteForm) => {
  // Validar el campo en tiempo real
  validateField(field);
};

// Validación inicial cuando se monta el componente
onMounted(() => {
  // Validar el formulario inicialmente
  validateForm();
});

const submit = async () => {
  if (validateForm()) {
    isLoading.value = true;
    
    // Simular delay para mostrar loading
    await new Promise(resolve => setTimeout(resolve, 1000));
    
    emit('submit', form);
    isLoading.value = false;
  }
};

const togglePassword = () => {
  showPassword.value = !showPassword.value;
};

const getFieldError = (field: keyof ClienteForm): string => {
  return errors.value[field] || '';
};

const hasFieldError = (field: keyof ClienteForm): boolean => {
  return !!errors.value[field];
};

const isFieldValid = (field: keyof ClienteForm): boolean => {
  return !errors.value[field] && form[field] !== '';
};

// Password strength logic
const getPasswordStrength = (index: number) => {
  const password = form.password_hash;
  if (!password) return 'bg-gray-200 dark:bg-gray-700';
  
  const strength = calculatePasswordStrength(password);
  
  if (index <= strength) {
    if (strength <= 1) return 'bg-red-500';
    if (strength <= 2) return 'bg-yellow-500';
    if (strength <= 3) return 'bg-blue-500';
    return 'bg-green-500';
  }
  
  return 'bg-gray-200 dark:bg-gray-700';
};

const calculatePasswordStrength = (password: string): number => {
  let strength = 0;
  
  if (password.length >= 6) strength++;
  if (password.length >= 8) strength++;
  if (/[a-z]/.test(password) && /[A-Z]/.test(password)) strength++;
  if (/[0-9]/.test(password)) strength++;
  if (/[^A-Za-z0-9]/.test(password)) strength++;
  
  return Math.min(strength, 4);
};

const getPasswordStrengthText = (): string => {
  const password = form.password_hash;
  if (!password) return 'Ingresa una contraseña';
  
  const strength = calculatePasswordStrength(password);
  
  if (strength <= 1) return 'Débil - Añade más caracteres';
  if (strength <= 2) return 'Regular - Mejora la complejidad';
  if (strength <= 3) return 'Buena - Casi perfecta';
  return 'Excelente - Contraseña muy segura';
};
</script>

<template>
  <div class="py-8">
    <div class="mx-auto max-w-4xl px-4 sm:px-6 lg:px-8">
      <!-- Header -->
      <div class="mb-8">
        <div class="flex items-center space-x-4">
          <Link :href="route('clientes.index')">
            <Button variant="outline" size="icon" class="h-10 w-10">
              <ArrowLeft class="w-5 h-5" />
            </Button>
          </Link>
          <div>
            <h1 class="text-3xl font-bold text-gray-900 dark:text-white">
              {{ isEdit ? 'Editar Cliente' : 'Crear Nuevo Cliente' }}
            </h1>
            <p class="mt-2 text-gray-600 dark:text-gray-400">
              {{ isEdit ? 'Actualiza la información del cliente' : 'Completa el formulario para registrar un nuevo cliente' }}
            </p>
          </div>
        </div>
      </div>

      <!-- Form Card -->
      <Card class="shadow-lg border-0 bg-gradient-to-br from-white to-gray-50 dark:from-gray-900 dark:to-gray-800">
        <CardHeader class="pb-6">
          <CardTitle class="flex items-center space-x-3">
            <div class="p-2 bg-gradient-to-br from-orange-400 to-red-500 rounded-lg">
              <component :is="isEdit ? User : UserPlus" class="w-6 h-6 text-white" />
            </div>
            <span class="text-2xl">{{ isEdit ? 'Editar Cliente' : 'Nuevo Cliente' }}</span>
          </CardTitle>
        </CardHeader>
        
        <CardContent class="space-y-8">
          <form @submit.prevent="submit" class="space-y-6">
            <!-- Personal Information Section -->
            <div class="space-y-6">
              <div class="border-b border-gray-200 dark:border-gray-700 pb-4">
                <h3 class="text-lg font-semibold text-gray-900 dark:text-white flex items-center space-x-2">
                  <User class="w-5 h-5 text-orange-500" />
                  <span>Información Personal</span>
                </h3>
                <p class="text-sm text-gray-600 dark:text-gray-400 mt-1">
                  Datos básicos del cliente
                </p>
              </div>

              <div class="grid grid-cols-1 gap-6 md:grid-cols-2">
                <!-- Nombre -->
                <div class="space-y-2">
                  <Label for="nombre" class="text-sm font-medium">
                    Nombre Completo <span class="text-red-500">*</span>
                  </Label>
                  <div class="relative">
                    <Input
                      id="nombre"
                      v-model="form.nombre"
                      type="text"
                      placeholder="Ingresa el nombre completo"
                      :class="[
                        'pl-10 transition-all duration-200',
                        hasFieldError('nombre') 
                          ? 'border-red-500 focus:border-red-500 focus:ring-red-500' 
                          : isFieldValid('nombre')
                            ? 'border-green-500 focus:border-green-500 focus:ring-green-500'
                            : 'border-gray-300 focus:border-orange-500 focus:ring-orange-500'
                      ]"
                      @input="handleInput('nombre')"
                      required
                    />
                    <User class="absolute left-3 top-1/2 transform -translate-y-1/2 w-4 h-4 text-gray-400" />
                    <div v-if="isFieldValid('nombre')" class="absolute right-3 top-1/2 transform -translate-y-1/2">
                      <CheckCircle class="w-4 h-4 text-green-500" />
                    </div>
                  </div>
                  <div v-if="hasFieldError('nombre')" class="flex items-center space-x-2 text-sm text-red-600">
                    <AlertCircle class="w-4 h-4" />
                    <span>{{ getFieldError('nombre') }}</span>
                  </div>
                </div>

                <!-- Email -->
                <div class="space-y-2">
                  <Label for="correo_electronico" class="text-sm font-medium">
                    Correo Electrónico
                  </Label>
                  <div class="relative">
                    <Input
                      id="correo_electronico"
                      v-model="form.correo_electronico"
                      type="email"
                      placeholder="cliente@ejemplo.com"
                      :class="[
                        'pl-10 transition-all duration-200',
                        hasFieldError('correo_electronico') 
                          ? 'border-red-500 focus:border-red-500 focus:ring-red-500' 
                          : isFieldValid('correo_electronico')
                            ? 'border-green-500 focus:border-green-500 focus:ring-green-500'
                            : 'border-gray-300 focus:border-orange-500 focus:ring-orange-500'
                      ]"
                      @input="handleInput('correo_electronico')"
                    />
                    <Mail class="absolute left-3 top-1/2 transform -translate-y-1/2 w-4 h-4 text-gray-400" />
                    <div v-if="isFieldValid('correo_electronico')" class="absolute right-3 top-1/2 transform -translate-y-1/2">
                      <CheckCircle class="w-4 h-4 text-green-500" />
                    </div>
                  </div>
                  <div v-if="hasFieldError('correo_electronico')" class="flex items-center space-x-2 text-sm text-red-600">
                    <AlertCircle class="w-4 h-4" />
                    <span>{{ getFieldError('correo_electronico') }}</span>
                  </div>
                </div>

                <!-- Teléfono -->
                <div class="space-y-2">
                  <Label for="telefono" class="text-sm font-medium">
                    Teléfono
                  </Label>
                  <div class="relative">
                    <Input
                      id="telefono"
                      v-model="form.telefono"
                      type="tel"
                      placeholder="+591 70000000"
                      :class="[
                        'pl-10 transition-all duration-200',
                        hasFieldError('telefono') 
                          ? 'border-red-500 focus:border-red-500 focus:ring-red-500' 
                          : isFieldValid('telefono')
                            ? 'border-green-500 focus:border-green-500 focus:ring-green-500'
                            : 'border-gray-300 focus:border-orange-500 focus:ring-orange-500'
                      ]"
                      @input="handleInput('telefono')"
                    />
                    <Phone class="absolute left-3 top-1/2 transform -translate-y-1/2 w-4 h-4 text-gray-400" />
                    <div v-if="isFieldValid('telefono')" class="absolute right-3 top-1/2 transform -translate-y-1/2">
                      <CheckCircle class="w-4 h-4 text-green-500" />
                    </div>
                  </div>
                  <div v-if="hasFieldError('telefono')" class="flex items-center space-x-2 text-sm text-red-600">
                    <AlertCircle class="w-4 h-4" />
                    <span>{{ getFieldError('telefono') }}</span>
                  </div>
                </div>

                <!-- Dirección -->
                <div class="space-y-2">
                  <Label for="direccion" class="text-sm font-medium">
                    Dirección
                  </Label>
                  <div class="relative">
                    <Input
                      id="direccion"
                      v-model="form.direccion"
                      type="text"
                      placeholder="Ingresa la dirección completa"
                      :class="[
                        'pl-10 transition-all duration-200',
                        hasFieldError('direccion') 
                          ? 'border-red-500 focus:border-red-500 focus:ring-red-500' 
                          : isFieldValid('direccion')
                            ? 'border-green-500 focus:border-green-500 focus:ring-green-500'
                            : 'border-gray-300 focus:border-orange-500 focus:ring-orange-500'
                      ]"
                      @input="handleInput('direccion')"
                    />
                    <MapPin class="absolute left-3 top-1/2 transform -translate-y-1/2 w-4 h-4 text-gray-400" />
                    <div v-if="isFieldValid('direccion')" class="absolute right-3 top-1/2 transform -translate-y-1/2">
                      <CheckCircle class="w-4 h-4 text-green-500" />
                    </div>
                  </div>
                  <div v-if="hasFieldError('direccion')" class="flex items-center space-x-2 text-sm text-red-600">
                    <AlertCircle class="w-4 h-4" />
                    <span>{{ getFieldError('direccion') }}</span>
                  </div>
                </div>
              </div>
            </div>

            <!-- Security Section -->
            <div class="space-y-6">
              <div class="border-b border-gray-200 dark:border-gray-700 pb-4">
                <h3 class="text-lg font-semibold text-gray-900 dark:text-white flex items-center space-x-2">
                  <Lock class="w-5 h-5 text-orange-500" />
                  <span>Seguridad</span>
                </h3>
                <p class="text-sm text-gray-600 dark:text-gray-400 mt-1">
                  {{ isEdit ? 'Actualiza la contraseña si es necesario' : 'Establece una contraseña segura' }}
                </p>
              </div>

              <div class="grid grid-cols-1 gap-6 md:grid-cols-2">
                <!-- Password -->
                <div class="space-y-2">
                  <Label for="password" class="text-sm font-medium">
                    {{ isEdit ? 'Nueva Contraseña' : 'Contraseña' }}
                    <span v-if="!isEdit" class="text-red-500">*</span>
                  </Label>
                  <div class="relative">
                    <Input
                      id="password"
                      v-model="form.password_hash"
                      :type="showPassword ? 'text' : 'password'"
                      :placeholder="isEdit ? 'Dejar en blanco para no cambiar' : 'Mínimo 6 caracteres'"
                      :class="[
                        'pr-10 transition-all duration-200',
                        hasFieldError('password_hash') 
                          ? 'border-red-500 focus:border-red-500 focus:ring-red-500' 
                          : isFieldValid('password_hash')
                            ? 'border-green-500 focus:border-green-500 focus:ring-green-500'
                            : 'border-gray-300 focus:border-orange-500 focus:ring-orange-500'
                      ]"
                      @input="handleInput('password_hash')"
                      :required="!isEdit"
                    />
                    <Lock class="absolute left-3 top-1/2 transform -translate-y-1/2 w-4 h-4 text-gray-400" />
                    <button
                      type="button"
                      @click="togglePassword"
                      class="absolute right-3 top-1/2 transform -translate-y-1/2 text-gray-400 hover:text-gray-600"
                    >
                      <Eye v-if="!showPassword" class="w-4 h-4" />
                      <EyeOff v-else class="w-4 h-4" />
                    </button>
                  </div>
                  <div v-if="hasFieldError('password_hash')" class="flex items-center space-x-2 text-sm text-red-600">
                    <AlertCircle class="w-4 h-4" />
                    <span>{{ getFieldError('password_hash') }}</span>
                  </div>
                  <p v-if="!isEdit" class="text-xs text-gray-500">
                    La contraseña debe tener al menos 6 caracteres
                  </p>
                </div>

                <!-- Password Strength Indicator -->
                <div class="space-y-2">
                  <Label class="text-sm font-medium">Fortaleza de la Contraseña</Label>
                  <div class="space-y-2">
                    <div class="flex space-x-1">
                      <div 
                        v-for="i in 4" 
                        :key="i"
                        :class="[
                          'h-2 flex-1 rounded-full transition-all duration-300',
                          getPasswordStrength(i)
                        ]"
                      ></div>
                    </div>
                    <p class="text-xs text-gray-500">
                      {{ getPasswordStrengthText() }}
                    </p>
                  </div>
                </div>
              </div>
            </div>

            <!-- Form Actions -->
            <div class="flex items-center justify-between pt-6 border-t border-gray-200 dark:border-gray-700">
              <div class="flex items-center space-x-4">
                <Link :href="route('clientes.index')">
                  <Button variant="outline" type="button">
                    <ArrowLeft class="w-4 h-4 mr-2" />
                    Cancelar
                  </Button>
                </Link>
                
                <div v-if="Object.keys(errors).length > 0" class="flex items-center space-x-2 text-sm text-red-600">
                  <AlertCircle class="w-4 h-4" />
                  <span>Por favor corrige los errores del formulario</span>
                </div>
              </div>

              <Button 
                type="submit" 
                :disabled="!isFormValid || isLoading"
                class="bg-gradient-to-r from-orange-500 to-red-500 hover:from-orange-600 hover:to-red-600 text-white px-8 py-3"
              >
                <Save v-if="!isLoading" class="w-4 h-4 mr-2" />
                <div v-else class="w-4 h-4 mr-2 border-2 border-white border-t-transparent rounded-full animate-spin"></div>
                {{ isLoading ? 'Guardando...' : (isEdit ? 'Actualizar Cliente' : 'Guardar Cliente') }}
              </Button>
            </div>
          </form>
        </CardContent>
      </Card>

      <!-- Help Section -->
      <div class="mt-8">
        <Card class="bg-blue-50 dark:bg-blue-900/20 border-blue-200 dark:border-blue-700">
          <CardContent class="p-6">
            <div class="flex items-start space-x-3">
              <div class="p-2 bg-blue-500 rounded-lg">
                <CheckCircle class="w-5 h-5 text-white" />
              </div>
              <div>
                <h4 class="font-medium text-blue-900 dark:text-blue-100">Consejos para un mejor registro</h4>
                <ul class="mt-2 text-sm text-blue-800 dark:text-blue-200 space-y-1">
                  <li>• Asegúrate de que el nombre esté completo y correcto</li>
                  <li>• El email debe ser válido para futuras comunicaciones</li>
                  <li>• La contraseña debe ser segura y fácil de recordar</li>
                  <li>• Los campos marcados con * son obligatorios</li>
                </ul>
              </div>
            </div>
          </CardContent>
        </Card>
      </div>
    </div>
  </div>
</template>

<style scoped>
.animate-spin {
  animation: spin 1s linear infinite;
}

@keyframes spin {
  from {
    transform: rotate(0deg);
  }
  to {
    transform: rotate(360deg);
  }
}
</style>
