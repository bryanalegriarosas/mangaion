<template>
  <div class="p-4" v-if="store.currentManga">
    <h1 class="text-3xl font-bold mb-4">
      {{ store.currentManga.title }}
    </h1>

    <p class="mb-6">
      {{ store.currentManga.description }}
    </p>

    <h2 class="text-xl font-semibold mb-2">Capítulos</h2>

    <div class="space-y-2">
      <div
        v-for="chapter in store.currentManga.chapters"
        :key="chapter.id"
        class="p-3 border rounded hover:bg-gray-100 cursor-pointer"
      >
        Capítulo {{ chapter.chapter_number }}
      </div>
    </div>
  </div>
</template>

<script setup lang="ts">
import { onMounted } from 'vue'
import { useRoute } from 'vue-router'
import { useMangaStore } from '../stores/Manga'

const route = useRoute()
const store = useMangaStore()

onMounted(() => {
  store.getManga(route.params.slug as string)
})
</script>
