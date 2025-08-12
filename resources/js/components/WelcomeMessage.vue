<script setup lang="ts">
import { ChefHat, UtensilsCrossed, Star, Clock } from 'lucide-vue-next';

interface Props {
    isLogin?: boolean;
}

const props = withDefaults(defineProps<Props>(), {
    isLogin: true,
});

const messages = {
    login: {
        title: "¡Bienvenido de vuelta!",
        subtitle: "Tu experiencia gastronómica te espera",
        features: [
            { icon: ChefHat, text: "Gestión completa del restaurante" },
            { icon: UtensilsCrossed, text: "Control de inventario en tiempo real" },
            { icon: Star, text: "Reportes detallados y análisis" },
            { icon: Clock, text: "Optimización de procesos" }
        ]
    },
    register: {
        title: "¡Únete a PandaBurger!",
        subtitle: "Comienza tu viaje en la gestión gastronómica",
        features: [
            { icon: ChefHat, text: "Sistema intuitivo y fácil de usar" },
            { icon: UtensilsCrossed, text: "Herramientas profesionales" },
            { icon: Star, text: "Soporte técnico especializado" },
            { icon: Clock, text: "Acceso 24/7 desde cualquier dispositivo" }
        ]
    }
};

const currentMessages = props.isLogin ? messages.login : messages.register;
</script>

<template>
    <div class="mb-8 p-6 rounded-2xl bg-gradient-to-br from-orange-500/10 via-red-500/10 to-orange-600/10 border border-orange-500/20 backdrop-blur-sm">
        <div class="text-center mb-6">
            <h3 class="text-xl font-bold text-white mb-2">{{ currentMessages.title }}</h3>
            <p class="text-orange-300/80 text-sm">{{ currentMessages.subtitle }}</p>
        </div>
        
        <!-- Grid responsivo: 4 columnas en desktop, 2 en tablet, 1 en móvil -->
        <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-3 lg:gap-4">
            <div 
                v-for="(feature, index) in currentMessages.features" 
                :key="index"
                class="flex flex-col items-center text-center gap-2 p-3 lg:p-4 rounded-lg bg-white/5 border border-orange-500/10 hover:bg-white/10 transition-all duration-300 hover-lift"
            >
                <component 
                    :is="feature.icon" 
                    class="h-6 w-6 lg:h-8 lg:w-8 text-orange-400 flex-shrink-0"
                />
                <span class="text-xs lg:text-sm text-slate-200 font-medium leading-tight">
                    {{ feature.text }}
                </span>
            </div>
        </div>
        
        <div class="mt-6 pt-4 border-t border-orange-500/20">
            <div class="flex items-center justify-center gap-2 text-xs text-orange-300/60">
                <ChefHat class="h-4 w-4" />
                <span>Sistema de Gestión Restaurante Profesional</span>
            </div>
        </div>
    </div>
</template>
