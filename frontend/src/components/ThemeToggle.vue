<template>
  <button
    @click="themeStore.toggle"
    :title="themeStore.isDark ? 'Cambiar a modo claro' : 'Cambiar a modo oscuro'"
    class="relative w-10 h-10 flex items-center justify-center rounded-xl transition-all duration-200
           text-gray-600 hover:text-gray-900 dark:text-gray-400 dark:hover:text-gray-200
           bg-gray-100 hover:bg-gray-200 dark:bg-white/5 dark:hover:bg-white/10"
  >
    <!-- ☀️ Sol — visible en modo oscuro (click → modo claro) -->
    <Transition name="theme-icon">
      <svg
        v-if="themeStore.isDark"
        key="sun"
        class="w-5 h-5 absolute"
        fill="none"
        stroke="currentColor"
        viewBox="0 0 24 24"
      >
        <path
          stroke-linecap="round"
          stroke-linejoin="round"
          stroke-width="2"
          d="M12 3v1m0 16v1m9-9h-1M4 12H3m15.364-6.364l-.707.707M6.343 17.657l-.707.707M17.657 17.657l-.707-.707M6.343 6.343l-.707-.707M12 7a5 5 0 110 10A5 5 0 0112 7z"
        />
      </svg>
    </Transition>

    <!-- 🌙 Luna — visible en modo claro (click → modo oscuro) -->
    <Transition name="theme-icon">
      <svg
        v-if="themeStore.isLight"
        key="moon"
        class="w-5 h-5 absolute"
        fill="none"
        stroke="currentColor"
        viewBox="0 0 24 24"
      >
        <path
          stroke-linecap="round"
          stroke-linejoin="round"
          stroke-width="2"
          d="M20.354 15.354A9 9 0 018.646 3.646 9.003 9.003 0 0012 21a9.003 9.003 0 008.354-5.646z"
        />
      </svg>
    </Transition>
  </button>
</template>

<script setup lang="ts">
import { useThemeStore } from '@/stores/Theme'

const themeStore = useThemeStore();
</script>

<style scoped>
/* Animación de entrada/salida del icono */
.theme-icon-enter-active,
.theme-icon-leave-active {
  transition: opacity 0.2s ease, transform 0.2s ease;
}

.theme-icon-enter-from {
  opacity: 0;
  transform: rotate(-90deg) scale(0.5);
}

.theme-icon-leave-to {
  opacity: 0;
  transform: rotate(90deg) scale(0.5);
}
</style>