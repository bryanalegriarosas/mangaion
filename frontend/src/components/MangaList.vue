<template>
  <section class="px-6">
    <div class="max-w-6xl mx-auto">
      <div class="flex items-center justify-between mb-6">
        <h2 class="text-xl font-bold text-gray-800">{{ title }}</h2>
        <button class="text-slate-600 hover:text-slate-700 text-sm font-medium transition-colors">
          Ver más →
        </button>
      </div>

      <div class="space-y-3">
        <div
          v-for="(chapter, index) in items"
          :key="chapter.id"
          class="group relative bg-gradient-to-r from-gray-50 to-gray-100 border border-gray-200 rounded-xl p-4 hover:border-slate-300 transition-all duration-300 hover:shadow-lg hover:shadow-slate-500/10"
        >
          <!-- Left side: Cover and info -->
          <div class="flex items-center gap-4">
            <!-- Rank number -->
            <div class="text-2xl font-bold text-gray-400 group-hover:text-slate-600 transition-colors w-8 text-center">
              {{ String(index + 1).padStart(2, '0') }}
            </div>
            
            <!-- Cover image -->
            <div class="relative overflow-hidden rounded-lg shadow-sm">
              <img
                :src="chapter.manga.cover_image || '/example.png'"
                class="w-14 h-20 object-cover transition-transform duration-300 group-hover:scale-110"
                :alt="chapter.manga.title"
              />
              <!-- New badge -->
              <div class="absolute top-1 right-1 bg-red-500 text-white text-xs px-1.5 py-0.5 rounded font-semibold shadow-sm">
                NEW
              </div>
            </div>

            <!-- Manga info -->
            <div class="flex-1 min-w-0">
              <div class="flex items-start justify-between gap-4">
                <div class="min-w-0 flex-1">
                  <h3 class="font-semibold text-gray-800 group-hover:text-slate-600 transition-colors truncate">
                    {{ chapter.manga.title }}
                  </h3>
                  <p class="text-sm text-gray-600 mt-1 flex items-center gap-2">
                    <span class="text-slate-600 font-medium">{{ chapter.title }}</span>
                    <span class="text-gray-400">•</span>
                    <span class="text-xs text-gray-500">Hace {{ chapter.time_ago || '2 horas' }}</span>
                  </p>
                  <div class="flex items-center gap-3 mt-2 text-xs text-gray-500">
                    <span class="flex items-center gap-1">
                      <svg class="w-3 h-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"></path>
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"></path>
                      </svg>
                      {{ chapter.views || '1.2K' }} vistas
                    </span>
                    <span class="flex items-center gap-1">
                      <svg class="w-3 h-3 fill-current text-yellow-500" viewBox="0 0 20 20">
                        <path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z"/>
                      </svg>
                      {{ chapter.rating || '4.8' }}
                    </span>
                  </div>
                </div>
                
                <!-- Action buttons -->
                <div class="flex items-center gap-2 opacity-0 group-hover:opacity-100 transition-opacity duration-300">
                  <button class="p-2 bg-slate-600 hover:bg-slate-700 rounded-lg transition-colors shadow-sm">
                    <svg class="w-4 h-4 text-white" fill="currentColor" viewBox="0 0 20 20">
                      <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zM9.555 7.168A1 1 0 008 8v4a1 1 0 001.555.832l3-2a1 1 0 000-1.664l-3-2z" clip-rule="evenodd"/>
                    </svg>
                  </button>
                  <button class="p-2 bg-gray-200 hover:bg-gray-300 rounded-lg transition-colors">
                    <svg class="w-4 h-4 text-gray-700" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                      <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4.318 6.318a4.5 4.5 0 000 6.364L12 20.364l7.682-7.682a4.5 4.5 0 00-6.364-6.364L12 7.636l-1.318-1.318a4.5 4.5 0 00-6.364 0z"></path>
                    </svg>
                  </button>
                </div>
              </div>
            </div>
          </div>
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
