<script setup lang="ts">
import InputError from '@/components/InputError.vue';
import TextLink from '@/components/TextLink.vue';
import WelcomeMessage from '@/components/WelcomeMessage.vue';
import RestaurantInput from '@/components/ui/restaurant-input.vue';
import { Button } from '@/components/ui/button';
import { Checkbox } from '@/components/ui/checkbox';
import { Label } from '@/components/ui/label';
import AuthBase from '@/layouts/AuthLayout.vue';
import { Head, useForm } from '@inertiajs/vue3';
import { LoaderCircle, Mail, Lock, ChefHat } from 'lucide-vue-next';

defineProps<{
    status?: string;
    canResetPassword: boolean;
}>();

const form = useForm({
    email: '',
    password: '',
    remember: false,
});

const submit = () => {
    form.post(route('login'), {
        onFinish: () => form.reset('password'),
    });
};
</script>

<template>
    <AuthBase title="Iniciar Sesión" description="Ingresa tus credenciales para acceder al sistema de gestión">
        <Head title="Iniciar Sesión - PandaBurger" />

        <!-- Welcome Message -->
        <WelcomeMessage :is-login="true" />

        <div v-if="status" class="mb-6 rounded-lg bg-green-500/10 border border-green-500/20 p-4 text-center text-sm font-medium text-green-400">
            {{ status }}
        </div>

        <form @submit.prevent="submit" class="flex flex-col gap-6">
            <div class="grid gap-4 sm:gap-6">
                <!-- Email field -->
                <div class="grid gap-2 sm:gap-3">
                    <Label for="email" class="text-sm font-medium text-slate-200">Correo Electrónico</Label>
                    <RestaurantInput
                        id="email"
                        type="email"
                        required
                        autofocus
                        :tabindex="1"
                        autocomplete="email"
                        v-model="form.email"
                        placeholder="tu@email.com"
                        :error="form.errors.email"
                    >
                        <template #icon>
                            <div class="absolute inset-y-0 left-0 flex items-center pl-3 pointer-events-none">
                                <Mail class="h-4 w-4 sm:h-5 sm:w-5 text-orange-400" />
                            </div>
                        </template>
                    </RestaurantInput>
                </div>

                <!-- Password field -->
                <div class="grid gap-2 sm:gap-3">
                    <div class="flex items-center justify-between">
                        <Label for="password" class="text-sm font-medium text-slate-200">Contraseña</Label>
                        <TextLink v-if="canResetPassword" :href="route('password.request')" class="text-xs sm:text-sm text-orange-400 hover:text-orange-300 transition-colors" :tabindex="5">
                            ¿Olvidaste tu contraseña?
                        </TextLink>
                    </div>
                    <RestaurantInput
                        id="password"
                        type="password"
                        required
                        :tabindex="2"
                        autocomplete="current-password"
                        v-model="form.password"
                        placeholder="Tu contraseña"
                        :error="form.errors.password"
                    >
                        <template #icon>
                            <div class="absolute inset-y-0 left-0 flex items-center pl-3 pointer-events-none">
                                <Lock class="h-4 w-4 sm:h-5 sm:w-5 text-orange-400" />
                            </div>
                        </template>
                    </RestaurantInput>
                </div>

                <!-- Remember me checkbox -->
                <div class="flex items-center justify-between">
                    <Label for="remember" class="flex items-center space-x-3 text-sm text-slate-200">
                        <Checkbox 
                            id="remember" 
                            v-model="form.remember" 
                            :tabindex="3"
                            class="border-orange-500/30 data-[state=checked]:bg-orange-500 data-[state=checked]:border-orange-500"
                        />
                        <span>Recordarme</span>
                    </Label>
                </div>

                <!-- Login button -->
                <Button 
                    type="submit" 
                    class="mt-4 sm:mt-6 w-full bg-gradient-to-r from-orange-500 to-red-500 hover:from-orange-600 hover:to-red-600 text-white font-semibold py-2 sm:py-3 rounded-lg sm:rounded-xl shadow-lg shadow-orange-500/25 transition-all duration-300 hover:shadow-xl hover:shadow-orange-500/40 hover:scale-[1.02] text-sm sm:text-base" 
                    :tabindex="4" 
                    :disabled="form.processing"
                >
                    <LoaderCircle v-if="form.processing" class="h-4 w-4 sm:h-5 sm:w-5 animate-spin mr-2" />
                    <ChefHat v-else class="h-4 w-4 sm:h-5 sm:w-5 mr-2" />
                    {{ form.processing ? 'Iniciando sesión...' : 'Iniciar Sesión' }}
                </Button>
            </div>

            <!-- Sign up link -->
            <div class="text-center text-xs sm:text-sm text-slate-300/80">
                ¿No tienes una cuenta?
                <TextLink 
                    :href="route('register')" 
                    :tabindex="5"
                    class="text-orange-400 hover:text-orange-300 font-medium transition-colors"
                >
                    Regístrate aquí
                </TextLink>
            </div>
        </form>
    </AuthBase>
</template>
