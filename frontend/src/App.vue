<template>
  <div class="min-h-screen bg-gray-50">
    <NavBar />
    <main>
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
