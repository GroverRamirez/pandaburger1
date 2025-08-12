<script setup lang="ts">
import AppLogoIcon from '@/components/AppLogoIcon.vue';
import FloatingParticles from '@/components/FloatingParticles.vue';
import { Link } from '@inertiajs/vue3';
import { ChefHat, UtensilsCrossed, Star } from 'lucide-vue-next';

defineProps<{
    title?: string;
    description?: string;
}>();
</script>

<template>
    <div class="relative min-h-svh overflow-hidden bg-gradient-to-br from-slate-900 via-slate-800 to-slate-900">
        <!-- Animated particles background -->
        <FloatingParticles />
        
        <!-- Background pattern -->
        <div class="absolute inset-0 opacity-5">
            <div class="absolute inset-0 bg-[radial-gradient(circle_at_1px_1px,rgba(249,115,22,0.3)_1px,transparent_0)] bg-[length:20px_20px]"></div>
        </div>
        
        <!-- Floating decorative icons -->
        <div class="absolute top-20 left-10 text-orange-500/10 animate-bounce">
            <ChefHat class="size-16" />
        </div>
        <div class="absolute top-40 right-16 text-red-500/10 animate-pulse">
            <UtensilsCrossed class="size-12" />
        </div>
        <div class="absolute bottom-32 left-20 text-yellow-500/10 animate-bounce" style="animation-delay: 1s;">
            <Star class="size-8" />
        </div>
        <div class="absolute bottom-20 right-10 text-orange-500/10 animate-pulse" style="animation-delay: 2s;">
            <ChefHat class="size-10" />
        </div>

        <!-- Main content -->
        <div class="relative flex min-h-svh flex-col items-center justify-center gap-6 lg:gap-8 p-4 sm:p-6 lg:p-10">
            <div class="w-full max-w-2xl lg:max-w-4xl">
                <div class="flex flex-col gap-6 lg:gap-8">
                    <!-- Header with logo and branding -->
                    <div class="flex flex-col items-center gap-4 lg:gap-6">
                        <Link href="/" class="group flex flex-col items-center gap-3 lg:gap-4">
                            <div class="mb-1 lg:mb-2">
                                <AppLogoIcon />
                            </div>
                            <div class="text-center">
                                <h1 class="text-xl lg:text-2xl font-bold bg-gradient-to-r from-orange-400 via-red-500 to-orange-600 bg-clip-text text-transparent">
                                    PandaBurger
                                </h1>
                                <p class="text-xs lg:text-sm text-orange-300/80 font-medium">Sistema de Gestión Restaurante</p>
                            </div>
                        </Link>
                        
                        <!-- Title and description -->
                        <div class="space-y-2 lg:space-y-3 text-center">
                            <h2 class="text-xl lg:text-2xl font-bold text-white">{{ title }}</h2>
                            <p class="text-center text-xs lg:text-sm text-slate-300/80 leading-relaxed max-w-lg mx-auto">{{ description }}</p>
                        </div>
                    </div>

                    <!-- Form container with glass effect -->
                    <div class="relative">
                        <div class="absolute inset-0 rounded-2xl bg-gradient-to-r from-orange-500/10 via-red-500/10 to-orange-600/10 blur-xl"></div>
                        <div class="relative rounded-2xl border border-orange-500/20 bg-white/5 backdrop-blur-sm p-6 lg:p-8 shadow-2xl shadow-orange-500/10">
                            <slot />
                        </div>
                    </div>

                    <!-- Footer branding -->
                    <div class="text-center">
                        <p class="text-xs text-slate-400/60">
                            © 2025 PandaBurger - Experiencia Gastronómica Digital
                        </p>
                    </div>
                </div>
            </div>
        </div>

        <!-- Bottom gradient accent -->
        <div class="absolute bottom-0 left-0 right-0 h-1 bg-gradient-to-r from-orange-500 via-red-500 to-orange-600"></div>
    </div>
</template>
