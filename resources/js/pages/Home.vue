<script setup lang="ts">
import { Head, Link } from '@inertiajs/vue3';
import { ref, computed } from 'vue';
import { ChefHat, UtensilsCrossed, Star, Clock, ShoppingCart, Heart, Search, Phone, MapPin, Mail } from 'lucide-vue-next';

interface Producto {
    id: number;
    nombre: string;
    descripcion: string;
    precio: number;
    imagen_url?: string;
    categoria: { id: number; nombre: string; };
}

interface Categoria {
    id: number;
    nombre: string;
    productos: Producto[];
}

interface Props {
    categorias: Categoria[];
    productosDestacados: Producto[];
    canLogin: boolean;
    canRegister: boolean;
}

const props = defineProps<Props>();
const searchQuery = ref('');
const selectedCategory = ref<number | null>(null);

const filteredCategorias = computed(() => {
    if (!searchQuery.value && !selectedCategory.value) return props.categorias;
    
    return props.categorias.filter(categoria => {
        const matchesSearch = !searchQuery.value || 
            categoria.nombre.toLowerCase().includes(searchQuery.value.toLowerCase()) ||
            categoria.productos.some(producto => 
                producto.nombre.toLowerCase().includes(searchQuery.value.toLowerCase())
            );
        const matchesCategory = !selectedCategory.value || categoria.id === selectedCategory.value;
        return matchesSearch && matchesCategory;
    });
});

const formatPrice = (price: number) => {
    return new Intl.NumberFormat('es-BO', {
        style: 'currency',
        currency: 'BOB'
    }).format(price);
};

const getProductImage = (producto: Producto) => {
    return producto.imagen_url || '/images/default-food.jpg';
};
</script>

<template>
    <Head title="PandaBurger - Experiencia Gastronómica" />

    <!-- Navigation -->
    <nav class="fixed top-0 left-0 right-0 z-50 bg-white/95 backdrop-blur-md border-b border-orange-200/50 shadow-lg">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="flex justify-between items-center h-16">
                <Link href="/" class="flex items-center space-x-3">
                    <div class="flex items-center justify-center w-10 h-10 rounded-xl bg-gradient-to-br from-orange-500 via-red-500 to-orange-600 text-white shadow-lg">
                        <ChefHat class="w-6 h-6" />
                    </div>
                    <span class="text-xl font-bold bg-gradient-to-r from-orange-600 to-red-600 bg-clip-text text-transparent">
                        PandaBurger
                    </span>
                </Link>

                <div class="hidden md:flex items-center space-x-8">
                    <Link href="#menu" class="text-gray-700 hover:text-orange-600 font-medium transition-colors">Menú</Link>
                    <Link href="#about" class="text-gray-700 hover:text-orange-600 font-medium transition-colors">Nosotros</Link>
                    <Link href="#contact" class="text-gray-700 hover:text-orange-600 font-medium transition-colors">Contacto</Link>
                </div>

                <div class="flex items-center space-x-4">
                    <Link v-if="canLogin" :href="route('login')" class="hidden sm:inline-flex items-center px-4 py-2 text-sm font-medium text-orange-600 hover:text-orange-700 transition-colors">
                        Iniciar Sesión
                    </Link>
                    <Link v-if="canRegister" :href="route('register')" class="inline-flex items-center px-4 py-2 text-sm font-medium text-white bg-gradient-to-r from-orange-500 to-red-500 rounded-lg hover:from-orange-600 hover:to-red-600 transition-all duration-300 shadow-lg hover:shadow-xl">
                        Registrarse
                    </Link>
                </div>
            </div>
        </div>
    </nav>

    <!-- Hero Section -->
    <section class="relative min-h-screen flex items-center justify-center bg-gradient-to-br from-slate-900 via-slate-800 to-slate-900 overflow-hidden">
        <div class="absolute inset-0 opacity-10">
            <div class="absolute inset-0 bg-[radial-gradient(circle_at_1px_1px,rgba(249,115,22,0.3)_1px,transparent_0)] bg-[length:20px_20px]"></div>
        </div>

        <div class="absolute top-20 left-10 text-orange-500/20 animate-bounce">
            <ChefHat class="w-16 h-16" />
        </div>
        <div class="absolute top-40 right-16 text-red-500/20 animate-pulse">
            <UtensilsCrossed class="w-12 h-12" />
        </div>
        <div class="absolute bottom-32 left-20 text-yellow-500/20 animate-bounce" style="animation-delay: 1s;">
            <Star class="w-8 h-8" />
        </div>

        <div class="relative z-10 text-center px-4 sm:px-6 lg:px-8 max-w-4xl mx-auto">
            <h1 class="text-4xl sm:text-5xl lg:text-6xl font-bold text-white mb-6">
                <span class="bg-gradient-to-r from-orange-400 via-red-500 to-orange-600 bg-clip-text text-transparent">
                    Experiencia Gastronómica
                </span>
                <br />
                <span class="text-white">Única</span>
            </h1>
            <p class="text-xl sm:text-2xl text-orange-200/80 mb-8 leading-relaxed">
                Descubre los sabores más auténticos y frescos en cada bocado
            </p>
            <div class="flex flex-col sm:flex-row gap-4 justify-center">
                <Link href="#menu" class="inline-flex items-center justify-center px-8 py-4 text-lg font-semibold text-white bg-gradient-to-r from-orange-500 to-red-500 rounded-xl hover:from-orange-600 hover:to-red-600 transition-all duration-300 shadow-lg hover:shadow-xl hover:scale-105">
                    <ShoppingCart class="w-5 h-5 mr-2" />
                    Ver Menú
                </Link>
                <Link href="#contact" class="inline-flex items-center justify-center px-8 py-4 text-lg font-semibold text-orange-500 border-2 border-orange-500 rounded-xl hover:bg-orange-500 hover:text-white transition-all duration-300">
                    <Phone class="w-5 h-5 mr-2" />
                    Ordenar Ahora
                </Link>
            </div>
        </div>
    </section>

    <!-- Featured Products Section -->
    <section class="py-20 bg-gradient-to-b from-slate-50 to-white">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="text-center mb-16">
                <h2 class="text-3xl sm:text-4xl font-bold text-gray-900 mb-4">
                    Productos <span class="text-orange-600">Destacados</span>
                </h2>
                <p class="text-lg text-gray-600 max-w-2xl mx-auto">
                    Nuestros platos más populares, preparados con ingredientes frescos y técnicas culinarias tradicionales
                </p>
            </div>

            <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-8">
                <div v-for="producto in productosDestacados" :key="producto.id" class="group bg-white rounded-2xl shadow-lg hover:shadow-2xl transition-all duration-300 overflow-hidden hover:scale-105">
                    <div class="relative h-48 bg-gradient-to-br from-orange-100 to-red-100">
                        <img :src="getProductImage(producto)" :alt="producto.nombre" class="w-full h-full object-cover group-hover:scale-110 transition-transform duration-300" />
                        <div class="absolute top-4 right-4">
                            <button class="p-2 bg-white/90 rounded-full hover:bg-white transition-colors">
                                <Heart class="w-5 h-5 text-red-500" />
                            </button>
                        </div>
                        <div class="absolute bottom-4 left-4">
                            <span class="px-3 py-1 bg-orange-500 text-white text-sm font-medium rounded-full">
                                {{ producto.categoria.nombre }}
                            </span>
                        </div>
                    </div>
                    <div class="p-6">
                        <h3 class="text-xl font-bold text-gray-900 mb-2">{{ producto.nombre }}</h3>
                        <p class="text-gray-600 mb-4 line-clamp-2">{{ producto.descripcion }}</p>
                        <div class="flex items-center justify-between">
                            <span class="text-2xl font-bold text-orange-600">{{ formatPrice(producto.precio) }}</span>
                            <button class="px-4 py-2 bg-gradient-to-r from-orange-500 to-red-500 text-white rounded-lg hover:from-orange-600 hover:to-red-600 transition-all duration-300">
                                <ShoppingCart class="w-4 h-4" />
                            </button>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- Menu Section -->
    <section id="menu" class="py-20 bg-white">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="text-center mb-16">
                <h2 class="text-3xl sm:text-4xl font-bold text-gray-900 mb-4">
                    Nuestro <span class="text-orange-600">Menú</span>
                </h2>
                <p class="text-lg text-gray-600 max-w-2xl mx-auto">
                    Explora nuestra amplia selección de platos organizados por categorías
                </p>
            </div>

            <!-- Search and Filter -->
            <div class="mb-12 flex flex-col sm:flex-row gap-4 justify-center items-center">
                <div class="relative max-w-md w-full">
                    <Search class="absolute left-3 top-1/2 transform -translate-y-1/2 w-5 h-5 text-gray-400" />
                    <input v-model="searchQuery" type="text" placeholder="Buscar productos..." class="w-full pl-10 pr-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-orange-500 focus:border-transparent" />
                </div>
                <select v-model="selectedCategory" class="px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-orange-500 focus:border-transparent">
                    <option :value="null">Todas las categorías</option>
                    <option v-for="categoria in categorias" :key="categoria.id" :value="categoria.id">
                        {{ categoria.nombre }}
                    </option>
                </select>
            </div>

            <!-- Categories and Products -->
            <div class="space-y-16">
                <div v-for="categoria in filteredCategorias" :key="categoria.id" class="category-section">
                    <div class="text-center mb-12">
                        <h3 class="text-2xl sm:text-3xl font-bold text-gray-900 mb-4">
                            {{ categoria.nombre }}
                        </h3>
                        <div class="w-24 h-1 bg-gradient-to-r from-orange-500 to-red-500 mx-auto rounded-full"></div>
                    </div>

                    <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 xl:grid-cols-4 gap-6">
                        <div v-for="producto in categoria.productos" :key="producto.id" class="group bg-white rounded-xl shadow-lg hover:shadow-2xl transition-all duration-300 overflow-hidden hover:scale-105 border border-gray-100">
                            <div class="relative h-40 bg-gradient-to-br from-orange-50 to-red-50">
                                <img :src="getProductImage(producto)" :alt="producto.nombre" class="w-full h-full object-cover group-hover:scale-110 transition-transform duration-300" />
                                <div class="absolute top-3 right-3">
                                    <button class="p-1.5 bg-white/90 rounded-full hover:bg-white transition-colors">
                                        <Heart class="w-4 h-4 text-red-500" />
                                    </button>
                                </div>
                            </div>
                            <div class="p-4">
                                <h4 class="text-lg font-bold text-gray-900 mb-2 line-clamp-1">{{ producto.nombre }}</h4>
                                <p class="text-gray-600 text-sm mb-3 line-clamp-2">{{ producto.descripcion }}</p>
                                <div class="flex items-center justify-between">
                                    <span class="text-xl font-bold text-orange-600">{{ formatPrice(producto.precio) }}</span>
                                    <button class="p-2 bg-gradient-to-r from-orange-500 to-red-500 text-white rounded-lg hover:from-orange-600 hover:to-red-600 transition-all duration-300">
                                        <ShoppingCart class="w-4 h-4" />
                                    </button>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- About Section -->
    <section id="about" class="py-20 bg-gradient-to-br from-orange-50 to-red-50">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="grid grid-cols-1 lg:grid-cols-2 gap-12 items-center">
                <div>
                    <h2 class="text-3xl sm:text-4xl font-bold text-gray-900 mb-6">
                        Sobre <span class="text-orange-600">PandaBurger</span>
                    </h2>
                    <p class="text-lg text-gray-600 mb-8 leading-relaxed">
                        Somos una empresa comprometida con la excelencia culinaria, ofreciendo los mejores sabores 
                        y la más alta calidad en cada uno de nuestros productos. Nuestra pasión por la gastronomía 
                        se refleja en cada plato que preparamos.
                    </p>
                    <div class="grid grid-cols-2 gap-6">
                        <div class="text-center">
                            <div class="w-16 h-16 bg-gradient-to-r from-orange-500 to-red-500 rounded-full flex items-center justify-center mx-auto mb-4">
                                <ChefHat class="w-8 h-8 text-white" />
                            </div>
                            <h3 class="font-bold text-gray-900 mb-2">Chefs Expertos</h3>
                            <p class="text-sm text-gray-600">Profesionales certificados</p>
                        </div>
                        <div class="text-center">
                            <div class="w-16 h-16 bg-gradient-to-r from-orange-500 to-red-500 rounded-full flex items-center justify-center mx-auto mb-4">
                                <Star class="w-8 h-8 text-white" />
                            </div>
                            <h3 class="font-bold text-gray-900 mb-2">Calidad Premium</h3>
                            <p class="text-sm text-gray-600">Ingredientes frescos</p>
                        </div>
                    </div>
                </div>
                <div class="relative">
                    <div class="bg-gradient-to-br from-orange-500 to-red-500 rounded-2xl p-8 text-white">
                        <h3 class="text-2xl font-bold mb-6">¿Por qué elegirnos?</h3>
                        <ul class="space-y-4">
                            <li class="flex items-center">
                                <Star class="w-5 h-5 mr-3 text-yellow-300" />
                                <span>Ingredientes frescos y de alta calidad</span>
                            </li>
                            <li class="flex items-center">
                                <Clock class="w-5 h-5 mr-3 text-yellow-300" />
                                <span>Servicio rápido y eficiente</span>
                            </li>
                            <li class="flex items-center">
                                <ChefHat class="w-5 h-5 mr-3 text-yellow-300" />
                                <span>Chefs profesionales certificados</span>
                            </li>
                            <li class="flex items-center">
                                <UtensilsCrossed class="w-5 h-5 mr-3 text-yellow-300" />
                                <span>Recetas tradicionales y modernas</span>
                            </li>
                        </ul>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- Contact Section -->
    <section id="contact" class="py-20 bg-slate-900">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="text-center mb-16">
                <h2 class="text-3xl sm:text-4xl font-bold text-white mb-4">Contáctanos</h2>
                <p class="text-lg text-gray-300 max-w-2xl mx-auto">
                    Estamos aquí para servirte. ¡Haz tu pedido ahora!
                </p>
            </div>

            <div class="grid grid-cols-1 md:grid-cols-3 gap-8">
                <div class="text-center">
                    <div class="w-16 h-16 bg-gradient-to-r from-orange-500 to-red-500 rounded-full flex items-center justify-center mx-auto mb-4">
                        <Phone class="w-8 h-8 text-white" />
                    </div>
                    <h3 class="text-xl font-bold text-white mb-2">Teléfono</h3>
                    <p class="text-gray-300">+591 63853001</p>
                </div>
                <div class="text-center">
                    <div class="w-16 h-16 bg-gradient-to-r from-orange-500 to-red-500 rounded-full flex items-center justify-center mx-auto mb-4">
                        <MapPin class="w-8 h-8 text-white" />
                    </div>
                    <h3 class="text-xl font-bold text-white mb-2">Dirección</h3>
                    <p class="text-gray-300">123 Calle Principal, Ciudad</p>
                </div>
                <div class="text-center">
                    <div class="w-16 h-16 bg-gradient-to-r from-orange-500 to-red-500 rounded-full flex items-center justify-center mx-auto mb-4">
                        <Mail class="w-8 h-8 text-white" />
                    </div>
                    <h3 class="text-xl font-bold text-white mb-2">Email</h3>
                    <p class="text-gray-300">info@pandaburger.com</p>
                </div>
            </div>
        </div>
    </section>

    <!-- Footer -->
    <footer class="bg-slate-800 py-12">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="text-center">
                <div class="flex items-center justify-center space-x-3 mb-6">
                    <div class="flex items-center justify-center w-10 h-10 rounded-xl bg-gradient-to-br from-orange-500 via-red-500 to-orange-600 text-white">
                        <ChefHat class="w-6 h-6" />
                    </div>
                    <span class="text-xl font-bold text-white">PandaBurger</span>
                </div>
                <p class="text-gray-300 mb-6">Experiencia Gastronómica Digital</p>
                <p class="text-sm text-gray-400">© 2025 PandaBurger - Todos los derechos reservados</p>
            </div>
        </div>
    </footer>
</template>

<style scoped>
.line-clamp-1 {
    display: -webkit-box;
    -webkit-line-clamp: 1;
    -webkit-box-orient: vertical;
    overflow: hidden;
}

.line-clamp-2 {
    display: -webkit-box;
    -webkit-line-clamp: 2;
    -webkit-box-orient: vertical;
    overflow: hidden;
}

.category-section {
    scroll-margin-top: 100px;
}
</style>
