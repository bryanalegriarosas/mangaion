<template>
  <div class="min-h-screen bg-white dark:bg-gray-900 transition-colors">
    <NavBar />
    <main class="text-gray-900 dark:text-gray-100">
      <router-view />
    </main>
  </div>
</template>

<script setup lang="ts">
import NavBar from '@/components/NavBar.vue'
</script>

<style scoped>
/* Add any global styles here */
</style>
