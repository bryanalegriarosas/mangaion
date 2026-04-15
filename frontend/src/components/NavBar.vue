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
          <!-- Theme Toggle -->
          <ThemeToggle />

          <!-- Auth -->
          <template v-if="!isAuthenticated">
            <router-link to="/login" class="px-4 py-2 text-sm font-medium transition-colors
                           text-gray-600 hover:text-gray-900
                           dark:text-gray-400 dark:hover:text-white">
              Iniciar sesión
            </router-link>

            <router-link to="/register" class="px-4 py-2 rounded-lg text-sm font-medium transition-colors
                           bg-indigo-600 hover:bg-indigo-700 text-white">
              Registrarse
            </router-link>
          </template>

          <template v-else>
            <!-- User Dropdown -->
            <div class="relative">
              <button v-if="store.user?.avatar" @click="toggleDropdown" class="flex items-center space-x-2 p-2 rounded-lg transition-colors
                       text-gray-600 hover:text-gray-900 hover:bg-gray-100
                       dark:text-gray-400 dark:hover:text-white dark:hover:bg-gray-800">
                <img :src="store.user?.avatar" alt="User avatar" class="w-8 h-8 rounded-full">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                  <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7" />
                </svg>
              </button>
              <button v-else @click="toggleDropdown" class="flex items-center space-x-2 p-2 rounded-lg transition-colors
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

              <!-- Dropdown Menu -->
              <div v-if="isDropdownOpen" @click.away="closeDropdown" class="absolute right-0 mt-2 w-48 rounded-lg shadow-lg
                       bg-white dark:bg-gray-800 border border-gray-200 dark:border-gray-700
                       py-1 z-50">
                <div class="px-4 py-3 border-b border-gray-200 dark:border-gray-700">
                  <p class="text-sm font-medium text-gray-900 dark:text-white">
                    {{ store.user?.name ?? store.user?.username }}
                  </p>
                  <p class="text-xs text-gray-500 dark:text-gray-400">
                    {{ store.user?.email }}
                  </p>
                </div>

                <router-link v-if="isAuthenticated" to="/dashboard" class="block px-4 py-2 text-sm text-gray-700 dark:text-gray-300
                         hover:bg-gray-100 dark:hover:bg-gray-700
                         transition-colors">
                  <div class="flex items-center space-x-2">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                      <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                        d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6" />
                    </svg>
                    <span>Dashboard</span>
                  </div>
                </router-link>

                <router-link to="/profile" class="block px-4 py-2 text-sm text-gray-700 dark:text-gray-300
                         hover:bg-gray-100 dark:hover:bg-gray-700
                         transition-colors">
                  <div class="flex items-center space-x-2">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                      <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                        d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z" />
                    </svg>
                    <span>Mi perfil</span>
                  </div>
                </router-link>

                <router-link to="/favorites" class="block px-4 py-2 text-sm text-gray-700 dark:text-gray-300
                         hover:bg-gray-100 dark:hover:bg-gray-700
                         transition-colors">
                  <div class="flex items-center space-x-2">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                      <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                        d="M4.318 6.318a4.5 4.5 0 000 6.364L12 20.364l7.682-7.682a4.5 4.5 0 00-6.364-6.364L12 7.636l-1.318-1.318a4.5 4.5 0 00-6.364 0z" />
                    </svg>
                    <span>Favoritos</span>
                  </div>
                </router-link>

                <router-link to="/reading-history" class="block px-4 py-2 text-sm text-gray-700 dark:text-gray-300
                         hover:bg-gray-100 dark:hover:bg-gray-700
                         transition-colors">
                  <div class="flex items-center space-x-2">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                      <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                        d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 11.754 5 10 5s-3.168.477-4.5 1.253m0 13C13.168 18.477 14.754 18 16.5 18c1.747 0 3.332-.477 4.5-1.253m0-13C19.832 5.477 18.246 5 16.5 5c-1.746 0-3.332.477-4.5 1.253" />
                    </svg>
                    <span>Historial</span>
                  </div>
                </router-link>

                <div class="border-t border-gray-200 dark:border-gray-700 pt-1">
                  <button @click="handleLogout" class="block w-full px-4 py-2 text-sm text-red-600 dark:text-red-400
                           hover:bg-red-50 dark:hover:bg-red-900/20
                           transition-colors text-left">
                    <div class="flex items-center space-x-2">
                      <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                          d="M17 16l4-4m0 0l-4 4m4-4H3m6 4h1a4 4 0 004-4V7a4 4 0 00-4-4H7a4 4 0 00-4 4v9a4 4 0 004 4h1" />
                      </svg>
                      <span>Cerrar sesión</span>
                    </div>
                  </button>
                </div>
              </div>
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
import { useAuthStore } from '@/stores/Auth';
import { logout } from '@/repositories/Auth';
import alertService from '@/helpers/sweetalert';

const isDropdownOpen = ref(false);
const router = useRouter();
const store = useAuthStore();

const isAuthenticated = computed(() => store.isAuth);
const userName = computed(() => store.user?.name || '');

const userInitial = computed(() => {
  return userName.value.charAt(0).toUpperCase();
});

const handleLogout = async () => {
  try {
    await logout();
    localStorage.removeItem('auth_token');
    store.clearToken();
    router.push('/')
    alertService.toast.success('¡Hasta pronto!');
  } catch (error) {
    console.error('Logout error:', error);
    await alertService.error('Error', 'No se pudo cerrar la sesión. Intenta de nuevo.')
  }
};

const toggleDropdown = () => {
  isDropdownOpen.value = !isDropdownOpen.value;
};

const closeDropdown = () => {
  isDropdownOpen.value = false;
};
</script>
