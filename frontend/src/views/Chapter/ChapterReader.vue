<template>
  <div class="bg-black min-h-screen text-white">

    <!-- Loading -->
    <div v-if="loading" class="text-center py-10">
      Cargando capítulo...
    </div>

    <!-- Contenido -->
    <div v-if="chapter" class="max-w-4xl mx-auto">

      <!-- Header -->
      <div class="p-4 text-center border-b border-gray-700">
        <h1 class="text-xl font-bold">
          Capítulo {{ chapter.chapter_number }}
        </h1>

        <p v-if="chapter.title" class="text-gray-400 text-sm">
          {{ chapter.title }}
        </p>
      </div>

      <!-- Imágenes -->
      <div class="flex flex-col items-center gap-2 py-4">
        <img
          v-for="page in chapter.pages"
          :key="page.id"
          :src="page.image_url"
          class="w-full max-w-3xl"
          loading="lazy"
        />
      </div>

    </div>
  </div>
</template>

<script setup lang="ts">
import { onMounted, ref } from 'vue';
import { useRoute } from 'vue-router';
import api from '@/services/Api';

const route = useRoute();

const chapter = ref<any>(null);
const loading = ref(false);

const fetchChapter = async () => {
  loading.value = true;

  try {
    const res = await api.get(`/chapters/${route.params.id}`);
    chapter.value = res.data;
  } finally {
    loading.value = false;
  }
};

onMounted(() => {
  fetchChapter();
});
</script>
