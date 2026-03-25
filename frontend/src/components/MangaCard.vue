<template>
  <div class="group cursor-pointer transform transition-all duration-300 hover:scale-105">
    <!-- Card container -->
    <div class="relative w-40 flex-shrink-0">
      <!-- Cover image with overlay -->
      <div class="relative overflow-hidden rounded-xl shadow-md hover:shadow-lg transition-shadow duration-300">
        <img
          :src="manga.cover_image || '/example.png'"
          class="h-56 w-full object-cover transition-transform duration-300 group-hover:scale-110"
          :alt="manga.title"
        />
        
        <!-- Gradient overlay -->
        <div class="absolute inset-0 bg-gradient-to-t from-black/60 via-transparent to-transparent opacity-0 group-hover:opacity-100 transition-opacity duration-300"></div>
        
        <!-- Rating badge -->
        <div class="absolute top-2 right-2 bg-gray-50/90 backdrop-blur-sm px-2 py-1 rounded-lg text-yellow-500 text-xs font-semibold flex items-center gap-1 shadow-sm border border-gray-200">
          <svg class="w-3 h-3 fill-current" viewBox="0 0 20 20">
            <path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z"/>
          </svg>
          {{ manga.rating || '4.8' }}
        </div>
        
        <!-- Status badge -->
        <div v-if="manga.status" class="absolute top-2 left-2 bg-green-500/90 backdrop-blur-sm px-2 py-1 rounded-lg text-white text-xs font-semibold shadow-sm">
          {{ manga.status }}
        </div>
        
        <!-- Play button overlay -->
        <div class="absolute inset-0 flex items-center justify-center opacity-0 group-hover:opacity-100 transition-opacity duration-300">
          <div class="bg-slate-700/90 backdrop-blur-sm p-3 rounded-full transform transition-transform duration-300 group-hover:scale-110 shadow-lg">
            <svg class="w-6 h-6 text-white" fill="currentColor" viewBox="0 0 20 20">
              <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zM9.555 7.168A1 1 0 008 8v4a1 1 0 001.555.832l3-2a1 1 0 000-1.664l-3-2z" clip-rule="evenodd"/>
            </svg>
          </div>
        </div>
      </div>
      
      <!-- Title and info -->
      <div class="mt-3">
        <h3 class="font-semibold text-sm leading-tight line-clamp-2 text-gray-800 group-hover:text-slate-600 transition-colors duration-200">
          {{ manga.title }}
        </h3>
        <div class="flex items-center justify-between mt-1">
          <p class="text-xs text-gray-600">
            {{ manga.chapters_count || '0' }} caps
          </p>
          <p class="text-xs text-gray-600">
            {{ manga.type || 'Manga' }}
          </p>
        </div>
      </div>
    </div>
  </div>
</template>

<script setup lang="ts">
defineProps<{
  manga: {
    id: number
    title: string
    cover_image: string | null
    rating?: string
    status?: string
    chapters_count?: number
    type?: string
  }
}>()
</script>