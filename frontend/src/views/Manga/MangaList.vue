<template>
  <div class="p-4">
    <h1 class="text-2xl font-bold mb-4">Mangas</h1>

    <div v-if="loading">Cargando...</div>

    <div v-else class="grid grid-cols-2 md:grid-cols-4 gap-4">
      <div
        v-for="manga in mangas"
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
import { onMounted, ref } from 'vue';
import { useRouter } from 'vue-router'
import { getMangas } from '@/repositories/Manga';
import type { Manga } from '@/types/Manga';

const router = useRouter();
const mangas = ref<Manga[]>([]);
const loading = ref<Boolean>(false);

const loadMangas = async () => {
  try {
    loading.value = true;
    const response = await getMangas();
    mangas.value = response.data;
  } catch (error) {
    console.error('Error loading mangas:', error);
  } finally {
    loading.value = false;
  }
}

onMounted(async () => {
  await loadMangas();
});
</script>

