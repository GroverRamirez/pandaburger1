<script setup lang="ts">
import InputError from '@/components/InputError.vue';
import TextLink from '@/components/TextLink.vue';
import WelcomeMessage from '@/components/WelcomeMessage.vue';
import RestaurantInput from '@/components/ui/restaurant-input.vue';
import { Button } from '@/components/ui/button';
import { Label } from '@/components/ui/label';
import AuthBase from '@/layouts/AuthLayout.vue';
import { Head, useForm } from '@inertiajs/vue3';
import { LoaderCircle, User, Mail, Lock, ChefHat, Shield } from 'lucide-vue-next';

const form = useForm({
    name: '',
    email: '',
    password: '',
    password_confirmation: '',
});

const submit = () => {
    form.post(route('register'), {
        onFinish: () => form.reset('password', 'password_confirmation'),
    });
};
</script>

<template>
    <AuthBase title="Crear Cuenta" description="Completa tus datos para crear tu cuenta en el sistema">
        <Head title="Registro - PandaBurger" />

        <!-- Welcome Message -->
        <WelcomeMessage :is-login="false" />

        <form @submit.prevent="submit" class="flex flex-col gap-6">
            <div class="grid gap-4 sm:gap-6">
                <!-- Name field -->
                <div class="grid gap-2 sm:gap-3">
                    <Label for="name" class="text-sm font-medium text-slate-200">Nombre Completo</Label>
                    <RestaurantInput
                        id="name"
                        type="text"
                        required
                        autofocus
                        :tabindex="1"
                        autocomplete="name"
                        v-model="form.name"
                        placeholder="Tu nombre completo"
                        :error="form.errors.name"
                    >
                        <template #icon>
                            <div class="absolute inset-y-0 left-0 flex items-center pl-3 pointer-events-none">
                                <User class="h-4 w-4 sm:h-5 sm:w-5 text-orange-400" />
                            </div>
                        </template>
                    </RestaurantInput>
                </div>

                <!-- Email field -->
                <div class="grid gap-2 sm:gap-3">
                    <Label for="email" class="text-sm font-medium text-slate-200">Correo Electrónico</Label>
                    <RestaurantInput
                        id="email"
                        type="email"
                        required
                        :tabindex="2"
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
                    <Label for="password" class="text-sm font-medium text-slate-200">Contraseña</Label>
                    <RestaurantInput
                        id="password"
                        type="password"
                        required
                        :tabindex="3"
                        autocomplete="new-password"
                        v-model="form.password"
                        placeholder="Crea una contraseña segura"
                        :error="form.errors.password"
                    >
                        <template #icon>
                            <div class="absolute inset-y-0 left-0 flex items-center pl-3 pointer-events-none">
                                <Lock class="h-4 w-4 sm:h-5 sm:w-5 text-orange-400" />
                            </div>
                        </template>
                    </RestaurantInput>
                </div>

                <!-- Confirm password field -->
                <div class="grid gap-2 sm:gap-3">
                    <Label for="password_confirmation" class="text-sm font-medium text-slate-200">Confirmar Contraseña</Label>
                    <RestaurantInput
                        id="password_confirmation"
                        type="password"
                        required
                        :tabindex="4"
                        autocomplete="new-password"
                        v-model="form.password_confirmation"
                        placeholder="Confirma tu contraseña"
                        :error="form.errors.password_confirmation"
                    >
                        <template #icon>
                            <div class="absolute inset-y-0 left-0 flex items-center pl-3 pointer-events-none">
                                <Shield class="h-4 w-4 sm:h-5 sm:w-5 text-orange-400" />
                            </div>
                        </template>
                    </RestaurantInput>
                </div>

                <!-- Create account button -->
                <Button 
                    type="submit" 
                    class="mt-4 sm:mt-6 w-full bg-gradient-to-r from-orange-500 to-red-500 hover:from-orange-600 hover:to-red-600 text-white font-semibold py-2 sm:py-3 rounded-lg sm:rounded-xl shadow-lg shadow-orange-500/25 transition-all duration-300 hover:shadow-xl hover:shadow-orange-500/40 hover:scale-[1.02] text-sm sm:text-base" 
                    tabindex="5" 
                    :disabled="form.processing"
                >
                    <LoaderCircle v-if="form.processing" class="h-4 w-4 sm:h-5 sm:w-5 animate-spin mr-2" />
                    <ChefHat v-else class="h-4 w-4 sm:h-5 sm:w-5 mr-2" />
                    {{ form.processing ? 'Creando cuenta...' : 'Crear Cuenta' }}
                </Button>
            </div>

            <!-- Login link -->
            <div class="text-center text-xs sm:text-sm text-slate-300/80">
                ¿Ya tienes una cuenta?
                <TextLink 
                    :href="route('login')" 
                    class="text-orange-400 hover:text-orange-300 font-medium transition-colors" 
                    :tabindex="6"
                >
                    Inicia sesión aquí
                </TextLink>
            </div>
        </form>
    </AuthBase>
</template>
