<template>
  <nav
    class="bg-white/90 dark:bg-gray-900/90 backdrop-blur-md border-b border-gray-200 dark:border-gray-800 sticky top-0 z-50 transition-colors">
    <div class="max-w-7xl mx-auto px-6">
      <div class="flex items-center justify-between h-16">

        <!-- Logo -->
        <div class="flex items-center">
          <router-link to="/" class="flex items-center space-x-2">
            <div
              class="w-8 h-8 bg-gradient-to-br from-indigo-500 to-purple-600 rounded-lg flex items-center justify-center">
              <span class="text-white font-bold text-lg">M</span>
            </div>
            <span class="text-xl font-bold text-gray-800 dark:text-white">
              Mangaion
            </span>
          </router-link>
        </div>

        <!-- Navigation Links -->
        <div class="hidden md:flex items-center space-x-8">
          <router-link to="/" class="px-3 py-2 text-sm font-medium transition-colors
                   text-gray-600 hover:text-gray-900
                   dark:text-gray-400 dark:hover:text-white"
            :class="{ 'text-indigo-600 dark:text-indigo-400': $route.path === '/' }">
            Inicio
          </router-link>

          <router-link to="/browse" class="px-3 py-2 text-sm font-medium transition-colors
                   text-gray-600 hover:text-gray-900
                   dark:text-gray-400 dark:hover:text-white">
            Explorar
          </router-link>

          <router-link to="/genres" class="px-3 py-2 text-sm font-medium transition-colors
                   text-gray-600 hover:text-gray-900
                   dark:text-gray-400 dark:hover:text-white">
            Géneros
          </router-link>

          <router-link to="/popular" class="px-3 py-2 text-sm font-medium transition-colors
                   text-gray-600 hover:text-gray-900
                   dark:text-gray-400 dark:hover:text-white">
            Popular
          </router-link>

          <router-link to="/latest" class="px-3 py-2 text-sm font-medium transition-colors
                   text-gray-600 hover:text-gray-900
                   dark:text-gray-400 dark:hover:text-white">
            Últimos
          </router-link>
        </div>

        <!-- Right side -->
        <div class="flex items-center space-x-4">

          <!-- Search -->
          <button class="p-2 rounded-lg transition-colors
                         text-gray-600 hover:text-gray-900 hover:bg-gray-100
                         dark:text-gray-400 dark:hover:text-white dark:hover:bg-gray-800">
            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
              <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z" />
            </svg>
          </button>

          <!-- Theme Toggle -->
          <ThemeToggle />

          <!-- Auth -->
          <template v-if="!isAuthenticated">
            <button class="px-4 py-2 text-sm font-medium transition-colors
                           text-gray-600 hover:text-gray-900
                           dark:text-gray-400 dark:hover:text-white">
              Iniciar sesión
            </button>

            <button class="px-4 py-2 rounded-lg text-sm font-medium transition-colors
                           bg-indigo-600 hover:bg-indigo-700 text-white"
              @click="register()"
            >
              Registrarse
            </button>
          </template>

          <template v-else>
            <!-- Notifications -->
            <button class="relative p-2 rounded-lg transition-colors
                           text-gray-600 hover:text-gray-900 hover:bg-gray-100
                           dark:text-gray-400 dark:hover:text-white dark:hover:bg-gray-800">
              <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                  d="M15 17h5l-1.405-1.405A2.032 2.032 0 0118 14.158V11a6.002 6.002 0 00-4-5.659V5a2 2 0 10-4 0v.341C7.67 6.165 6 8.388 6 11v3.159c0 .538-.214 1.055-.595 1.436L4 17h5m6 0v1a3 3 0 11-6 0v-1m6 0H9" />
              </svg>
              <span class="absolute top-1 right-1 w-2 h-2 bg-red-500 rounded-full"></span>
            </button>

            <!-- User -->
            <div class="relative">
              <button class="flex items-center space-x-2 p-2 rounded-lg transition-colors
                             text-gray-600 hover:text-gray-900 hover:bg-gray-100
                             dark:text-gray-400 dark:hover:text-white dark:hover:bg-gray-800">
                <div
                  class="w-8 h-8 bg-gradient-to-br from-indigo-400 to-purple-500 rounded-full flex items-center justify-center">
                  <span class="text-white text-sm font-medium">
                    {{ userInitial }}
                  </span>
                </div>

                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                  <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7" />
                </svg>
              </button>
            </div>
          </template>

          <!-- Mobile button -->
          <button class="md:hidden p-2 rounded-lg transition-colors
                         text-gray-600 hover:text-gray-900 hover:bg-gray-100
                         dark:text-gray-400 dark:hover:text-white dark:hover:bg-gray-800">
            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
              <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16" />
            </svg>
          </button>

        </div>
      </div>
    </div>

    <!-- Mobile menu -->
    <div class="md:hidden bg-white dark:bg-gray-900 border-t border-gray-200 dark:border-gray-800">
      <div class="px-2 pt-2 pb-3 space-y-1">
        <router-link to="/" class="block px-3 py-2 rounded-md text-base font-medium
                                  text-gray-600 hover:text-gray-900 hover:bg-gray-50
                                  dark:text-gray-400 dark:hover:text-white dark:hover:bg-gray-800">
          Inicio
        </router-link>

        <router-link to="/browse" class="block px-3 py-2 rounded-md text-base font-medium
                                        text-gray-600 hover:text-gray-900 hover:bg-gray-50
                                        dark:text-gray-400 dark:hover:text-white dark:hover:bg-gray-800">
          Explorar
        </router-link>

        <router-link to="/genres" class="block px-3 py-2 rounded-md text-base font-medium
                                        text-gray-600 hover:text-gray-900 hover:bg-gray-50
                                        dark:text-gray-400 dark:hover:text-white dark:hover:bg-gray-800">
          Géneros
        </router-link>

        <router-link to="/popular" class="block px-3 py-2 rounded-md text-base font-medium
                                         text-gray-600 hover:text-gray-900 hover:bg-gray-50
                                         dark:text-gray-400 dark:hover:text-white dark:hover:bg-gray-800">
          Popular
        </router-link>

        <router-link to="/latest" class="block px-3 py-2 rounded-md text-base font-medium
                                        text-gray-600 hover:text-gray-900 hover:bg-gray-50
                                        dark:text-gray-400 dark:hover:text-white dark:hover:bg-gray-800">
          Últimos
        </router-link>
      </div>
    </div>
  </nav>
</template>

<script setup lang="ts">
import { computed, ref } from 'vue';
import { useRouter } from 'vue-router';
import ThemeToggle from './ThemeToggle.vue';

const router = useRouter();

const register = () => {
  router.push('/register');
};

// Mock auth state - replace with actual auth logic
const isAuthenticated = ref(false)
const userName = ref('John Doe')

const userInitial = computed(() => {
  return userName.value.charAt(0).toUpperCase()
})
</script>
