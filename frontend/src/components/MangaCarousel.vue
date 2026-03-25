<template>
  <section class="px-6 bg-gray-100/20 backdrop-blur-sm">
    <div class="max-w-6xl mx-auto">
      <div class="flex items-center justify-between mb-6">
        <h2 class="text-xl font-bold text-gray-800">{{ title }}</h2>
        <button class="text-slate-600 hover:text-slate-700 text-sm font-medium transition-colors">
          Ver todo →
        </button>
      </div>

      <div class="flex gap-4 overflow-x-auto pb-4">
        <MangaCard
          v-for="item in items"
          :key="item.id"
          :manga="item"
        />
      </div>
    </div>
  </section>
</template>

<script setup lang="ts">
import MangaCard from './MangaCard.vue'

defineProps<{
  title: string
  items: any[]
}>()
</script>
