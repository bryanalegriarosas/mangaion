<template>
  <div class="min-h-screen transition-colors duration-300
              bg-white dark:bg-[#0d0d12]
              text-gray-900 dark:text-gray-100">

    <!-- ===================== -->
    <!-- 💀 SKELETON / LOADING -->
    <!-- ===================== -->
    <template v-if="loading">
      <div class="px-6 py-24 max-w-6xl mx-auto space-y-10 animate-pulse">
        <div class="h-64 rounded-2xl bg-gray-200 dark:bg-gray-800/50"></div>
        <div class="grid grid-cols-6 gap-4">
          <div v-for="i in 6" :key="i" class="h-20 rounded-xl bg-gray-200 dark:bg-gray-800/50"></div>
        </div>
        <div class="grid grid-cols-2 md:grid-cols-4 gap-4">
          <div v-for="i in 8" :key="i" class="h-48 rounded-xl bg-gray-200 dark:bg-gray-800/50"></div>
        </div>
      </div>
    </template>

    <!-- ============ -->
    <!-- ❌ ERROR     -->
    <!-- ============ -->
    <template v-else-if="error">
      <div class="flex flex-col items-center justify-center min-h-[60vh] gap-4 text-center px-6">
        <p class="text-5xl">😵</p>
        <p class="text-xl font-bold text-gray-900 dark:text-white">Algo salió mal</p>
        <p class="text-sm text-gray-500 dark:text-gray-400">{{ error }}</p>
        <button class="mt-2 bg-red-500 hover:bg-red-400 px-6 py-3 rounded-xl text-white font-semibold transition-colors"
          @click="loadHomePage()">
          Reintentar
        </button>
      </div>
    </template>

    <template v-else>

      <!-- ================= -->
      <!-- � HOME VERSION  -->
      <!-- ================= -->
      <div class="space-y-0">

        <!-- HERO -->
        <section class="relative overflow-hidden min-h-[90vh] flex items-center">

          <!-- Fondo base -->
          <div class="absolute inset-0 transition-colors duration-300
                      bg-white dark:bg-[#0d0d12]"></div>

          <!-- Gradientes — dark -->
          <div class="absolute inset-0 hidden dark:block
                      bg-[radial-gradient(ellipse_at_top_left,_#1a0a2e_0%,_transparent_60%)]"></div>
          <div class="absolute inset-0 hidden dark:block
                      bg-[radial-gradient(ellipse_at_bottom_right,_#1a0010_0%,_transparent_60%)]"></div>

          <!-- Gradientes — light -->
          <div class="absolute inset-0 block dark:hidden
                      bg-[radial-gradient(ellipse_at_top_left,_#fce7f3_0%,_transparent_60%)]"></div>
          <div class="absolute inset-0 block dark:hidden
                      bg-[radial-gradient(ellipse_at_bottom_right,_#ede9fe_0%,_transparent_60%)]"></div>

          <!-- Panel lines decorativas -->
          <div class="absolute inset-0 overflow-hidden opacity-[0.04]">
            <div class="absolute top-0 left-1/4 w-px h-full bg-gray-900 dark:bg-white"></div>
            <div class="absolute top-0 left-2/4 w-px h-full bg-gray-900 dark:bg-white"></div>
            <div class="absolute top-0 left-3/4 w-px h-full bg-gray-900 dark:bg-white"></div>
            <div class="absolute top-1/3 left-0 w-full h-px bg-gray-900 dark:bg-white"></div>
            <div class="absolute top-2/3 left-0 w-full h-px bg-gray-900 dark:bg-white"></div>
          </div>

          <!-- Orbs -->
          <div class="absolute top-20 left-10 w-72 h-72 rounded-full blur-3xl animate-pulse
                      bg-purple-200/50 dark:bg-purple-900/20"></div>
          <div class="absolute bottom-20 right-10 w-96 h-96 rounded-full blur-3xl animate-pulse delay-1000
                      bg-pink-200/50 dark:bg-red-900/20"></div>

          <div class="relative px-6 py-24 text-center w-full">
            <div class="max-w-5xl mx-auto">

              <!-- Badge -->
              <div v-if="!store.isAuth" class="inline-flex items-center gap-2 px-4 py-2 rounded-full text-sm font-medium mb-8 backdrop-blur-sm
                          text-red-600 dark:text-red-400
                          bg-red-50 border border-red-200
                          dark:bg-red-500/10 dark:border-red-500/30">
                <span class="w-2 h-2 bg-red-400 rounded-full animate-pulse"></span>
                Nueva temporada disponible
              </div>

              <!-- Título -->
              <h1 class="text-6xl md:text-8xl font-black mb-4 leading-none tracking-tight">
                <span class="text-gray-900 dark:text-white">Lee y comparte</span>
                <br>
                <span class="bg-gradient-to-r from-red-400 via-pink-400 to-purple-400 bg-clip-text text-transparent"
                  style="font-size: 1.15em;">
                  manga
                </span>
              </h1>

              <!-- Subtítulo -->
              <p class="text-lg md:text-xl mb-10 max-w-2xl mx-auto leading-relaxed
                        text-gray-600 dark:text-gray-400">
                Miles de historias te esperan. La plataforma de manga más grande en español, gratis y sin anuncios.
              </p>

              <!-- Stats -->
              <div class="flex flex-wrap justify-center gap-12 mb-12">
                <div v-for="stat in stats" :key="stat.label" class="text-center">
                  <div class="text-4xl font-black text-gray-900 dark:text-white">{{ stat.value }}</div>
                  <div class="text-sm mt-1 uppercase tracking-widest text-gray-500">{{ stat.label }}</div>
                </div>
              </div>

              <!-- CTAs -->
              <div class="flex flex-col sm:flex-row justify-center gap-4">
                <template v-if="!store.isAuth">
                  <RouterLink to="/register">
                    <button class="bg-red-500 hover:bg-red-400 px-8 py-4 rounded-xl font-bold text-lg text-white
                                   shadow-[0_0_30px_rgba(239,68,68,0.3)] hover:shadow-[0_0_50px_rgba(239,68,68,0.5)]
                                   transform hover:scale-105 transition-all duration-200 flex items-center gap-2">
                      Registrarse gratis
                    </button>
                  </RouterLink>
                  <RouterLink to="/catalog">
                    <button class="px-8 py-4 rounded-xl font-bold text-lg backdrop-blur-sm
                                   transform hover:scale-105 transition-all duration-200 flex items-center gap-2
                                   border border-gray-300 dark:border-gray-700
                                   bg-white/80 dark:bg-white/5
                                   hover:bg-gray-100 dark:hover:bg-white/10
                                   text-gray-700 dark:text-gray-300
                                   hover:text-gray-900 dark:hover:text-white">
                      Explorar catálogo
                    </button>
                  </RouterLink>
                </template>
                <template v-else>
                  <RouterLink to="/dashboard">
                    <button class="bg-red-500 hover:bg-red-400 px-8 py-4 rounded-xl font-bold text-lg text-white
                                   shadow-[0_0_30px_rgba(239,68,68,0.3)] hover:shadow-[0_0_50px_rgba(239,68,68,0.5)]
                                   transform hover:scale-105 transition-all duration-200 flex items-center gap-2">
                      Ir a mi Dashboard
                    </button>
                  </RouterLink>
                  <RouterLink to="/mangas">
                    <button class="px-8 py-4 rounded-xl font-bold text-lg backdrop-blur-sm
                                   transform hover:scale-105 transition-all duration-200 flex items-center gap-2
                                   border border-gray-300 dark:border-gray-700
                                   bg-white/80 dark:bg-white/5
                                   hover:bg-gray-100 dark:hover:bg-white/10
                                   text-gray-700 dark:text-gray-300
                                   hover:text-gray-900 dark:hover:text-white">
                      Explorar mangas
                    </button>
                  </RouterLink>
                </template>
              </div>

            </div>
          </div>
        </section>

        <!-- CATEGORIES -->
        <section class="px-6 py-16 border-t border-gray-200 dark:border-white/5">
          <div class="max-w-6xl mx-auto">
            <h2 class="text-sm font-semibold uppercase tracking-[0.2em] mb-6
                       text-gray-400 dark:text-gray-500">
              Explorar por género
            </h2>
            <div class="grid grid-cols-2 sm:grid-cols-3 md:grid-cols-4 lg:grid-cols-6 gap-3">
              <RouterLink v-for="genre in genres" :key="genre.slug" :to="`/catalog?genre=${genre.slug}`">
                <div :class="genre.bg" class="rounded-xl p-4 text-center transition-all duration-200 cursor-pointer
                         border border-gray-200 dark:border-white/5
                         hover:scale-105 hover:brightness-105 dark:hover:brightness-125">
                  <div class="text-2xl mb-2">{{ genre.emoji }}</div>
                  <div class="text-sm font-semibold text-gray-700 dark:text-white/80">{{ genre.name }}</div>
                </div>
              </RouterLink>
            </div>
          </div>
        </section>

        <!-- TRENDING -->
        <MangaCarousel title="🔥 Tendencias" :items="trending" />

        <!-- POPULAR ESTA SEMANA -->
        <section class="px-6 py-16 border-t border-gray-200 dark:border-white/5" v-if="popular.length">
          <div class="max-w-6xl mx-auto">
            <div class="flex items-center justify-between mb-8">
              <div>
                <h2 class="text-sm font-semibold uppercase tracking-[0.2em] mb-1
                           text-gray-400 dark:text-gray-500">Esta semana</h2>
                <p class="text-2xl font-black text-gray-900 dark:text-white">📈 Lo más popular</p>
              </div>
              <RouterLink to="/catalog?sort=popular" class="text-sm font-medium transition-colors
                       text-red-500 dark:text-red-400
                       hover:text-red-400 dark:hover:text-red-300">
                Ver todo →
              </RouterLink>
            </div>

            <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-4">
              <RouterLink v-for="(manga, index) in popular" :key="manga.id" :to="`/manga/${manga.id}`"
                class="relative group cursor-pointer">
                <div :class="rankGlow[index]"
                  class="absolute -inset-px rounded-xl blur opacity-0 group-hover:opacity-100 transition duration-300">
                </div>
                <div class="relative rounded-xl p-5 transition-all duration-200
                            bg-white dark:bg-gray-900
                            border border-gray-200 dark:border-white/10
                            hover:border-gray-300 dark:hover:border-white/20
                            shadow-sm dark:shadow-none">
                  <div class="flex items-start justify-between mb-4">
                    <div :class="rankColors[index]" class="text-3xl font-black">
                      #{{ index + 1 }}
                    </div>
                    <div v-if="manga.growth !== null" :class="manga.growth >= 0
                      ? 'bg-green-100 text-green-700 dark:bg-green-500/15 dark:text-green-400'
                      : 'bg-red-100 text-red-700 dark:bg-red-500/15 dark:text-red-400'"
                      class="px-2 py-1 rounded-lg text-xs font-bold">
                      {{ manga.growth >= 0 ? '+' : '' }}{{ manga.growth }}%
                    </div>
                  </div>
                  <div class="flex gap-4">
                    <img :src="manga.cover ?? '/placeholder-cover.jpg'" :alt="manga.title" class="w-16 h-20 object-cover rounded-lg shadow-lg flex-shrink-0
                             bg-gray-100 dark:bg-gray-800">
                    <div class="flex-1 min-w-0">
                      <h3 class="font-bold mb-1 text-sm leading-tight line-clamp-2
                                 text-gray-900 dark:text-white">
                        {{ manga.title }}
                      </h3>
                      <p class="text-xs mb-2 text-gray-500">{{ manga.genre }}</p>
                      <div class="flex items-center gap-1 text-xs text-gray-500 dark:text-gray-400">
                        <svg class="w-3 h-3 fill-yellow-400 flex-shrink-0" viewBox="0 0 20 20">
                          <path
                            d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z" />
                        </svg>
                        <span class="font-semibold text-gray-900 dark:text-white">{{ manga.rating ?? '—' }}</span>
                        <span class="text-gray-300 dark:text-gray-600">·</span>
                        <span>{{ formatReaders(manga.readers) }}</span>
                      </div>
                    </div>
                  </div>
                </div>
              </RouterLink>
            </div>
          </div>
        </section>

        <!-- ÚLTIMOS CAPÍTULOS -->
        <MangaList title="🆕 Últimos capítulos" :items="latestChapters" />

        <!-- CTA -->
        <section v-if="!store.isAuth" class="px-6 py-24 border-t border-gray-200 dark:border-white/5">
          <div class="max-w-3xl mx-auto text-center">
            <p class="text-sm font-semibold uppercase tracking-[0.2em] mb-4
                      text-red-500 dark:text-red-400">
              Para creadores
            </p>
            <h2 class="text-4xl md:text-5xl font-black mb-4 leading-tight
                       text-gray-900 dark:text-white">
              ¿Tienes tu propio manga?
              <br>
              <span class="text-gray-400 dark:text-gray-500">Compártelo aquí.</span>
            </h2>
            <RouterLink to="/register">
              <button class="bg-red-500 hover:bg-red-400 px-10 py-4 rounded-xl font-bold text-lg text-white
                             shadow-[0_0_40px_rgba(239,68,68,0.25)] hover:shadow-[0_0_60px_rgba(239,68,68,0.4)]
                             transform hover:scale-105 transition-all duration-200">
                Crear cuenta gratis
              </button>
            </RouterLink>
          </div>
        </section>

      </div>

    </template>
  </div>
</template>

<script setup lang="ts">
import { rankColors, rankGlow } from '@/helpers/utils'
import { formatReaders } from '@/helpers'
import { ref, onMounted } from 'vue'
import { RouterLink } from 'vue-router'
import { useAuthStore } from '@/stores/Auth'
import MangaCarousel from '@/components/MangaCarousel.vue'
import MangaList from '@/components/MangaList.vue'
import { getHomePage } from '@/repositories/HomeService'
import type {
  Manga,
  PopularManga,
  ChapterVersion,
  ContinueReading,
} from '@/types/Manga'
import alertService from '@/helpers/sweetalert'

const store = useAuthStore();
const loading = ref(true)
const error = ref<string | null>(null)
const trending = ref<Manga[]>([])
const popular = ref<PopularManga[]>([])
const latestChapters = ref<ChapterVersion[]>([])
const continueReading = ref<ContinueReading[]>([])
const recommended = ref<Manga[]>([])

// Static data
const stats = [
  { value: '10K+', label: 'Títulos' },
  { value: '500K+', label: 'Capítulos' },
  { value: '50K+', label: 'Usuarios' },
]

// Clases completas en el array para que Tailwind no las purgue
const genres = [
  { name: 'Acción', emoji: '⚔️', slug: 'action', bg: 'bg-red-50    dark:bg-gradient-to-br dark:from-red-900/60    dark:to-orange-900/40' },
  { name: 'Romance', emoji: '💕', slug: 'romance', bg: 'bg-pink-50   dark:bg-gradient-to-br dark:from-pink-900/60   dark:to-purple-900/40' },
  { name: 'Drama', emoji: '🎭', slug: 'drama', bg: 'bg-blue-50   dark:bg-gradient-to-br dark:from-blue-900/60   dark:to-cyan-900/40' },
  { name: 'Comedia', emoji: '😂', slug: 'comedy', bg: 'bg-green-50  dark:bg-gradient-to-br dark:from-green-900/60  dark:to-emerald-900/40' },
  { name: 'Fantasía', emoji: '✨', slug: 'fantasy', bg: 'bg-purple-50 dark:bg-gradient-to-br dark:from-purple-900/60 dark:to-indigo-900/40' },
  { name: 'Sci-Fi', emoji: '🔮', slug: 'sci-fi', bg: 'bg-gray-100  dark:bg-gradient-to-br dark:from-slate-800/80  dark:to-gray-900/60' },
]

const loadHomePage = async (): Promise<void> => {
  loading.value = true
  error.value = null
  try {
    const response = await getHomePage()
    trending.value = response?.trending
    popular.value = response?.popular
    latestChapters.value = response?.latestChapters || []

    if (response.user) {
      store.hydrateFromHome(response?.user)
      continueReading.value = response?.continueReading || []
      recommended.value = response?.recommended || []
    }
  } catch (e: any) {
    error.value = 'No se pudo cargar el contenido. Intenta de nuevo.'
    console.error('[Home] loadHomePage error:', e)
    
    // Mostrar alerta de error con SweetAlert2
    await alertService.error(
      'Error de carga',
      'No se pudo cargar el contenido de la página principal. Por favor, intenta recargar la página.',
      {
        confirmButtonText: 'Recargar página',
        cancelButtonText: 'Cancelar',
      }
    ).then((result) => {
      if (result.isConfirmed) {
        window.location.reload()
      }
    })
  } finally {
    loading.value = false
  }
}

onMounted(async () => {
  await loadHomePage();
});
</script>
