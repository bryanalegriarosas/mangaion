<template>
  <section class="px-6">
    <h2 class="text-xl font-bold mb-4">{{ title }}</h2>

    <div class="flex gap-4 overflow-x-auto pb-4">
      <MangaCard
        v-for="item in items"
        :key="item.id"
        :manga="item"
      />
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
