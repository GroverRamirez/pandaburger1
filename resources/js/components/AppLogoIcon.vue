<script setup lang="ts">
import { UtensilsCrossed, ChefHat, Pizza } from 'lucide-vue-next';
</script>

<template>
    <div class="relative flex aspect-square size-12 items-center justify-center rounded-xl bg-gradient-to-br from-orange-500 via-red-500 to-orange-600 text-white shadow-lg shadow-orange-500/25 transition-all duration-300 hover:scale-105 hover:shadow-xl hover:shadow-orange-500/40">
        <div class="absolute inset-0 rounded-xl bg-gradient-to-br from-orange-400/20 to-red-600/20"></div>
        <div class="relative z-10 flex items-center justify-center">
            <Pizza class="size-6" />
        </div>
        <div class="absolute -top-1 -right-1 flex size-4 items-center justify-center rounded-full bg-yellow-400 text-xs font-bold text-orange-800">
            <ChefHat class="size-3" />
        </div>
    </div>
</template>
