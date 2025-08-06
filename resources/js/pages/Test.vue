<script setup lang="ts">
import { Head } from '@inertiajs/vue3';
</script>

<template>
    <div class="min-h-screen bg-gray-100 flex items-center justify-center">
        <Head title="Test Page" />
        
        <div class="bg-white p-8 rounded-lg shadow-md">
            <h1 class="text-2xl font-bold text-gray-900 mb-4">Test Page</h1>
            <p class="text-gray-600">If you can see this, the frontend is working correctly!</p>
            <div class="mt-4">
                <a href="/login" class="text-blue-500 hover:text-blue-700">Go to Login</a>
            </div>
        </div>
    </div>
</template> 