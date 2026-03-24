<template>
  <div class="p-4" v-if="manga">
    <h1 class="text-3xl font-bold mb-4">
      {{ manga?.title }}
    </h1>

    <p class="mb-6">
      {{ manga?.description }}
    </p>

    <h2 class="text-xl font-semibold mb-2">Capítulos</h2>

    <div class="space-y-2">
      <div
        v-for="chapter in manga?.chapters"
        :key="chapter.id"
        class="p-3 border rounded hover:bg-gray-100 cursor-pointer"
        @click="router.push(`/chapter/${chapter.id}`)"
      >
        Capítulo {{ chapter?.chapter_number }}
      </div>
    </div>

    <button
      @click="router.push(`/manga/${manga?.slug}/upload`)"
      class="bg-green-500 text-white px-3 py-1 rounded mb-4"
    >
      Subir capítulo
    </button>
  </div>
</template>

<script setup lang="ts">
import { onMounted, ref } from 'vue';
import { useRoute, useRouter } from 'vue-router';
import { getManga } from '@/repositories/Manga';
import type { Manga } from '@/types/Manga';

const route = useRoute();
const router = useRouter();
const manga = ref<Manga | null>(null);

const loadManga = async () => {
  try {
    const data = await getManga(String(route.params.slug));
    manga.value = data;
  } catch (error) {
    console.error('Error loading manga:', error);
  }
}

onMounted(async () => {
  await loadManga();
});
</script>
