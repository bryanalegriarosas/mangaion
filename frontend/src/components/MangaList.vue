<template>
  <section class="px-6">
    <h2 class="text-xl font-bold mb-4">{{ title }}</h2>

    <div class="space-y-3">
      <div
        v-for="chapter in items"
        :key="chapter.id"
        class="flex items-center gap-4 bg-gray-900 p-3 rounded-xl"
      >
        <img
          :src="chapter.manga.cover_image || '/example.png'"
          class="w-12 h-16 object-cover rounded"
        />

        <div>
          <p class="font-semibold">{{ chapter.manga.title }}</p>
          <p class="text-sm text-gray-400">
            {{ chapter.title }}
          </p>
        </div>
      </div>
    </div>
  </section>
</template>

<script setup lang="ts">
defineProps<{
  title: string
  items: any[]
}>()
</script>
