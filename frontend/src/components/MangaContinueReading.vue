<template>
  <section class="px-6">
    <h2 class="text-xl font-bold mb-4">
      📖 Continuar leyendo
    </h2>

    <div class="flex gap-4 overflow-x-auto">
      <div
        v-for="item in items"
        :key="item.manga.id"
        class="w-48 bg-gray-900 rounded-xl p-3"
      >
        <img
          :src="item.manga.cover_image || '/example.png'"
          class="h-56 w-full object-cover rounded"
        />

        <p class="mt-2 font-semibold truncate">
          {{ item.manga.title }}
        </p>

        <p class="text-sm text-gray-400">
          Cap: {{ item.chapter.title }}
        </p>

        <p class="text-xs text-gray-500">
          Página {{ item.page }}
        </p>
      </div>
    </div>
  </section>
</template>

<script setup lang="ts">
defineProps<{
  items: any[]
}>()
</script>
