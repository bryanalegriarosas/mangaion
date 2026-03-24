<template>
  <div class="p-4 max-w-2xl mx-auto">
    <h1 class="text-2xl font-bold mb-4">Subir capítulo</h1>

    <input
      v-model="title"
      placeholder="Título"
      class="border p-2 w-full mb-3"
    />

    <input
      v-model="chapterNumber"
      type="number"
      placeholder="Número de capítulo"
      class="border p-2 w-full mb-3"
    />

    <input type="file" multiple @change="handleFiles" class="mb-4" />

    <!-- Preview -->
    <div class="grid grid-cols-3 gap-2 mb-4">
      <img
        v-for="(img, i) in preview"
        :key="i"
        :src="img"
        class="w-full h-32 object-cover"
      />
    </div>

    <button
      @click="submit"
      class="bg-blue-500 text-white px-4 py-2 rounded"
    >
      Subir
    </button>
  </div>
</template>

<script setup lang="ts">
import { ref } from 'vue';
import { useRoute } from 'vue-router';
import api from '@/services/Api';

const route = useRoute();

const title = ref('');
const chapterNumber = ref<number | null>(null);
const images = ref<File[]>([]);
const preview = ref<string[]>([]);

const handleFiles = (e: Event) => {
  const files = (e.target as HTMLInputElement).files;
  if (!files) return;

  images.value = Array.from(files);

  preview.value = images.value.map(file =>
    URL.createObjectURL(file)
  );
};

const submit = async () => {
  const formData = new FormData()

  formData.append('title', title.value || '');
  formData.append('chapter_number', String(chapterNumber.value));

  images.value.forEach((file) => {
    formData.append('images[]', file);
  });

  await api.post(`/mangas/${route.params.slug}/chapters`, formData, {
    headers: {
      'Content-Type': 'multipart/form-data',
    },
  });

  alert('Capítulo creado 🚀');
};
</script>
