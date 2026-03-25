<template>
  <nav class="bg-gray-50/90 backdrop-blur-md border-b border-gray-200 sticky top-0 z-50">
    <div class="max-w-7xl mx-auto px-6">
      <div class="flex items-center justify-between h-16">
        <!-- Logo -->
        <div class="flex items-center">
          <router-link to="/" class="flex items-center space-x-2">
            <div class="w-8 h-8 bg-gradient-to-br from-indigo-500 to-purple-600 rounded-lg flex items-center justify-center">
              <span class="text-white font-bold text-lg">M</span>
            </div>
            <span class="text-xl font-bold text-gray-800">Mangaion</span>
          </router-link>
        </div>

        <!-- Navigation Links -->
        <div class="hidden md:flex items-center space-x-8">
          <router-link 
            to="/" 
            class="text-gray-600 hover:text-gray-900 px-3 py-2 text-sm font-medium transition-colors"
            :class="{ 'text-indigo-600': $route.path === '/' }"
          >
            Inicio
          </router-link>
          <router-link 
            to="/browse" 
            class="text-gray-600 hover:text-gray-900 px-3 py-2 text-sm font-medium transition-colors"
          >
            Explorar
          </router-link>
          <router-link 
            to="/genres" 
            class="text-gray-600 hover:text-gray-900 px-3 py-2 text-sm font-medium transition-colors"
          >
            Géneros
          </router-link>
          <router-link 
            to="/popular" 
            class="text-gray-600 hover:text-gray-900 px-3 py-2 text-sm font-medium transition-colors"
          >
            Popular
          </router-link>
          <router-link 
            to="/latest" 
            class="text-gray-600 hover:text-gray-900 px-3 py-2 text-sm font-medium transition-colors"
          >
            Últimos
          </router-link>
        </div>

        <!-- Right side buttons -->
        <div class="flex items-center space-x-4">
          <!-- Search -->
          <button class="text-gray-600 hover:text-gray-900 p-2 rounded-lg hover:bg-gray-100 transition-colors">
            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
              <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"></path>
            </svg>
          </button>

          <!-- Auth buttons -->
          <template v-if="!isAuthenticated">
            <button class="text-gray-600 hover:text-gray-900 px-4 py-2 text-sm font-medium transition-colors">
              Iniciar sesión
            </button>
            <button class="bg-indigo-600 hover:bg-indigo-700 text-white px-4 py-2 rounded-lg text-sm font-medium transition-colors">
              Registrarse
            </button>
          </template>
          <template v-else>
            <!-- Notifications -->
            <button class="text-gray-600 hover:text-gray-900 p-2 rounded-lg hover:bg-gray-100 transition-colors relative">
              <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 17h5l-1.405-1.405A2.032 2.032 0 0118 14.158V11a6.002 6.002 0 00-4-5.659V5a2 2 0 10-4 0v.341C7.67 6.165 6 8.388 6 11v3.159c0 .538-.214 1.055-.595 1.436L4 17h5m6 0v1a3 3 0 11-6 0v-1m6 0H9"></path>
              </svg>
              <span class="absolute top-1 right-1 w-2 h-2 bg-red-500 rounded-full"></span>
            </button>
            
            <!-- User menu -->
            <div class="relative">
              <button class="flex items-center space-x-2 text-gray-600 hover:text-gray-900 p-2 rounded-lg hover:bg-gray-100 transition-colors">
                <div class="w-8 h-8 bg-gradient-to-br from-indigo-400 to-purple-500 rounded-full flex items-center justify-center">
                  <span class="text-white text-sm font-medium">{{ userInitial }}</span>
                </div>
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                  <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"></path>
                </svg>
              </button>
            </div>
          </template>

          <!-- Mobile menu button -->
          <button class="md:hidden text-gray-600 hover:text-gray-900 p-2 rounded-lg hover:bg-gray-100 transition-colors">
            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
              <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16"></path>
            </svg>
          </button>
        </div>
      </div>
    </div>

    <!-- Mobile menu (hidden by default) -->
    <div v-if="false" class="md:hidden bg-white border-t border-gray-200">
      <div class="px-2 pt-2 pb-3 space-y-1">
        <router-link to="/" class="block px-3 py-2 text-gray-600 hover:text-gray-900 hover:bg-gray-50 rounded-md text-base font-medium">
          Inicio
        </router-link>
        <router-link to="/browse" class="block px-3 py-2 text-gray-600 hover:text-gray-900 hover:bg-gray-50 rounded-md text-base font-medium">
          Explorar
        </router-link>
        <router-link to="/genres" class="block px-3 py-2 text-gray-600 hover:text-gray-900 hover:bg-gray-50 rounded-md text-base font-medium">
          Géneros
        </router-link>
        <router-link to="/popular" class="block px-3 py-2 text-gray-600 hover:text-gray-900 hover:bg-gray-50 rounded-md text-base font-medium">
          Popular
        </router-link>
        <router-link to="/latest" class="block px-3 py-2 text-gray-600 hover:text-gray-900 hover:bg-gray-50 rounded-md text-base font-medium">
          Últimos
        </router-link>
      </div>
    </div>
  </nav>
</template>

<script setup lang="ts">
import { computed, ref } from 'vue'
import { useRoute } from 'vue-router'

const route = useRoute()

// Mock auth state - replace with actual auth logic
const isAuthenticated = ref(false)
const userName = ref('John Doe')

const userInitial = computed(() => {
  return userName.value.charAt(0).toUpperCase()
})
</script>
