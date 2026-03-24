<template>
  <div class="w-40 flex-shrink-0 cursor-pointer">
    <img
      :src="manga.cover_image || '/example.png'"
      class="rounded-xl h-56 w-full object-cover"
    />
    <p class="mt-2 text-sm font-medium truncate">
      {{ manga.title }}
    </p>
  </div>
</template>

<script setup lang="ts">
defineProps<{
  manga: {
    id: number
    title: string
    cover_image: string | null
  }
}>()
</script>