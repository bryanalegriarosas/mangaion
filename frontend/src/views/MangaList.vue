<template>
  <div class="p-4">
    <h1 class="text-2xl font-bold mb-4">Mangas</h1>

    <div v-if="store.loading">Cargando...</div>

    <div v-else class="grid grid-cols-2 md:grid-cols-4 gap-4">
      <div
        v-for="manga in store.mangas"
        :key="manga.id"
        @click="router.push(`/manga/${manga.slug}`)"
        class="border p-4 rounded hover:shadow cursor-pointer"
      >
        <h2 class="font-semibold">{{ manga.title }}</h2>
      </div>
    </div>
  </div>
</template>

<script setup lang="ts">
import { onMounted } from 'vue'
import { useMangaStore } from '../stores/Manga'
import { useRouter } from 'vue-router'

const router = useRouter()
const store = useMangaStore()

onMounted(() => {
  store.getMangas()
});
</script>
