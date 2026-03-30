<template>
  <div class="min-h-screen bg-[#0d0d12] text-gray-100">

    <!-- ===================== -->
    <!-- 💀 SKELETON / LOADING -->
    <!-- ===================== -->
    <template v-if="loading">
      <div class="px-6 py-24 max-w-6xl mx-auto space-y-10 animate-pulse">
        <div class="h-64 bg-gray-800/50 rounded-2xl"></div>
        <div class="grid grid-cols-6 gap-4">
          <div v-for="i in 6" :key="i" class="h-20 bg-gray-800/50 rounded-xl"></div>
        </div>
        <div class="grid grid-cols-2 md:grid-cols-4 gap-4">
          <div v-for="i in 8" :key="i" class="h-48 bg-gray-800/50 rounded-xl"></div>
        </div>
      </div>
    </template>

    <!-- ============ -->
    <!-- ❌ ERROR     -->
    <!-- ============ -->
    <template v-else-if="error">
      <div class="flex flex-col items-center justify-center min-h-[60vh] gap-4 text-center px-6">
        <p class="text-5xl">😵</p>
        <p class="text-xl font-bold text-white">Algo salió mal</p>
        <p class="text-gray-400 text-sm">{{ error }}</p>
        <button
          class="mt-2 bg-red-500 hover:bg-red-400 px-6 py-3 rounded-xl text-white font-semibold transition-colors"
          @click="getHomePage()"
        >
          Reintentar
        </button>
      </div>
    </template>

    <template v-else>

      <!-- ================= -->
      <!-- 🔓 GUEST VERSION  -->
      <!-- ================= -->
      <div v-if="!authStore.isAuth">

        <!-- HERO -->
        <section class="relative overflow-hidden min-h-[90vh] flex items-center">
          <div class="absolute inset-0 bg-[#0d0d12]"></div>
          <div class="absolute inset-0 bg-[radial-gradient(ellipse_at_top_left,_#1a0a2e_0%,_transparent_60%)]"></div>
          <div class="absolute inset-0 bg-[radial-gradient(ellipse_at_bottom_right,_#1a0010_0%,_transparent_60%)]"></div>

          <div class="absolute inset-0 overflow-hidden opacity-5">
            <div class="absolute top-0 left-1/4 w-px h-full bg-white"></div>
            <div class="absolute top-0 left-2/4 w-px h-full bg-white"></div>
            <div class="absolute top-0 left-3/4 w-px h-full bg-white"></div>
            <div class="absolute top-1/3 left-0 w-full h-px bg-white"></div>
            <div class="absolute top-2/3 left-0 w-full h-px bg-white"></div>
          </div>

          <div class="absolute top-20 left-10 w-72 h-72 bg-purple-900/20 rounded-full blur-3xl animate-pulse"></div>
          <div class="absolute bottom-20 right-10 w-96 h-96 bg-red-900/20 rounded-full blur-3xl animate-pulse delay-1000"></div>

          <div class="relative px-6 py-24 text-center w-full">
            <div class="max-w-5xl mx-auto">
              <div class="inline-flex items-center gap-2 bg-red-500/10 border border-red-500/30 px-4 py-2 rounded-full text-red-400 text-sm font-medium mb-8 backdrop-blur-sm">
                <span class="w-2 h-2 bg-red-400 rounded-full animate-pulse"></span>
                Nueva temporada disponible
              </div>

              <h1 class="text-6xl md:text-8xl font-black mb-4 leading-none tracking-tight">
                <span class="text-white">Lee y comparte</span>
                <br>
                <span
                  class="bg-gradient-to-r from-red-400 via-pink-400 to-purple-400 bg-clip-text text-transparent"
                  style="font-size: 1.15em;"
                >
                  manga
                </span>
              </h1>

              <p class="text-lg md:text-xl text-gray-400 mb-10 max-w-2xl mx-auto leading-relaxed">
                Miles de historias te esperan. La plataforma de manga más grande en español, gratis y sin anuncios.
              </p>

              <div class="flex flex-wrap justify-center gap-12 mb-12">
                <div v-for="stat in stats" :key="stat.label" class="text-center">
                  <div class="text-4xl font-black text-white">{{ stat.value }}</div>
                  <div class="text-sm text-gray-500 mt-1 uppercase tracking-widest">{{ stat.label }}</div>
                </div>
              </div>

              <div class="flex flex-col sm:flex-row justify-center gap-4">
                <RouterLink to="/register">
                  <button class="bg-red-500 hover:bg-red-400 px-8 py-4 rounded-xl font-bold text-lg shadow-[0_0_30px_rgba(239,68,68,0.3)] hover:shadow-[0_0_50px_rgba(239,68,68,0.5)] transform hover:scale-105 transition-all duration-200 text-white flex items-center gap-2">
                    Registrarse gratis
                  </button>
                </RouterLink>
                <RouterLink to="/catalog">
                  <button class="border border-gray-700 hover:border-gray-500 px-8 py-4 rounded-xl font-bold text-lg bg-white/5 hover:bg-white/10 backdrop-blur-sm transform hover:scale-105 transition-all duration-200 flex items-center gap-2 text-gray-300 hover:text-white">
                    Explorar catálogo
                  </button>
                </RouterLink>
              </div>
            </div>
          </div>
        </section>

        <!-- CATEGORIES -->
        <section class="px-6 py-16 border-t border-white/5">
          <div class="max-w-6xl mx-auto">
            <h2 class="text-sm font-semibold uppercase tracking-[0.2em] text-gray-500 mb-6">
              Explorar por género
            </h2>
            <div class="grid grid-cols-2 sm:grid-cols-3 md:grid-cols-4 lg:grid-cols-6 gap-3">
              <RouterLink
                v-for="genre in genres"
                :key="genre.slug"
                :to="`/catalog?genre=${genre.slug}`"
              >
                <div
                  :class="genre.bg"
                  class="rounded-xl p-4 text-center transition-all duration-200 hover:scale-105 hover:brightness-125 border border-white/5 cursor-pointer"
                >
                  <div class="text-2xl mb-2">{{ genre.emoji }}</div>
                  <div class="text-sm font-semibold text-white/80">{{ genre.name }}</div>
                </div>
              </RouterLink>
            </div>
          </div>
        </section>

        <!-- TRENDING -->
        <MangaCarousel title="🔥 Tendencias" :items="trending" />

        <!-- POPULAR ESTA SEMANA -->
        <section class="px-6 py-16 border-t border-white/5" v-if="popular.length">
          <div class="max-w-6xl mx-auto">
            <div class="flex items-center justify-between mb-8">
              <div>
                <h2 class="text-sm font-semibold uppercase tracking-[0.2em] text-gray-500 mb-1">Esta semana</h2>
                <p class="text-2xl font-black text-white">📈 Lo más popular</p>
              </div>
              <RouterLink to="/catalog?sort=popular" class="text-red-400 hover:text-red-300 text-sm font-medium transition-colors">
                Ver todo →
              </RouterLink>
            </div>

            <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-4">
              <RouterLink
                v-for="(manga, index) in popular"
                :key="manga.id"
                :to="`/manga/${manga.id}`"
                class="relative group cursor-pointer"
              >
                <div
                  :class="rankGlow[index]"
                  class="absolute -inset-px rounded-xl blur opacity-0 group-hover:opacity-100 transition duration-300"
                ></div>
                <div class="relative bg-gray-900 border border-white/10 rounded-xl p-5 hover:border-white/20 transition-all duration-200">
                  <div class="flex items-start justify-between mb-4">
                    <div :class="rankColors[index]" class="text-3xl font-black">
                      #{{ index + 1 }}
                    </div>
                    <div
                      v-if="manga.growth !== null"
                      :class="manga.growth >= 0
                        ? 'bg-green-500/15 text-green-400'
                        : 'bg-red-500/15 text-red-400'"
                      class="px-2 py-1 rounded-lg text-xs font-bold"
                    >
                      {{ manga.growth >= 0 ? '+' : '' }}{{ manga.growth }}%
                    </div>
                  </div>
                  <div class="flex gap-4">
                    <img
                      :src="manga.cover ?? '/placeholder-cover.jpg'"
                      :alt="manga.title"
                      class="w-16 h-20 object-cover rounded-lg shadow-lg flex-shrink-0 bg-gray-800"
                    >
                    <div class="flex-1 min-w-0">
                      <h3 class="font-bold mb-1 text-white text-sm leading-tight line-clamp-2">
                        {{ manga.title }}
                      </h3>
                      <p class="text-xs text-gray-500 mb-2">{{ manga.genre }}</p>
                      <div class="flex items-center gap-1 text-xs text-gray-400">
                        <svg class="w-3 h-3 fill-yellow-400 flex-shrink-0" viewBox="0 0 20 20">
                          <path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z"/>
                        </svg>
                        <span class="font-semibold text-white">{{ manga.rating ?? '—' }}</span>
                        <span class="text-gray-600">·</span>
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
        <section class="px-6 py-24 border-t border-white/5">
          <div class="max-w-3xl mx-auto text-center">
            <p class="text-sm font-semibold uppercase tracking-[0.2em] text-red-400 mb-4">Para creadores</p>
            <h2 class="text-4xl md:text-5xl font-black text-white mb-4 leading-tight">
              ¿Tienes tu propio manga?
              <br>
              <span class="text-gray-500">Compártelo aquí.</span>
            </h2>
            <RouterLink to="/register">
              <button class="bg-red-500 hover:bg-red-400 px-10 py-4 rounded-xl font-bold text-lg text-white shadow-[0_0_40px_rgba(239,68,68,0.25)] hover:shadow-[0_0_60px_rgba(239,68,68,0.4)] transform hover:scale-105 transition-all duration-200">
                Crear cuenta gratis
              </button>
            </RouterLink>
          </div>
        </section>

      </div>

      <!-- ================= -->
      <!-- 🔐 AUTH VERSION  -->
      <!-- ================= -->
      <div v-else class="space-y-0">

        <!-- WELCOME BANNER -->
        <section class="px-6 pt-12 pb-8 border-b border-white/5">
          <div class="max-w-6xl mx-auto">
            <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4">
              <div>
                <p class="text-sm text-gray-500 uppercase tracking-widest mb-1">Bienvenido de vuelta</p>
                <h1 class="text-3xl font-black text-white">
                  {{ authStore.user?.name }}<span class="text-red-400">.</span>
                </h1>
              </div>
              <div class="flex gap-6">
                <div class="text-center">
                  <div class="text-xl font-bold text-white">{{ authStore.user?.chaptersRead ?? 0 }}</div>
                  <div class="text-xs text-gray-500 uppercase tracking-wider">Caps. leídos</div>
                </div>
                <div class="text-center">
                  <div class="text-xl font-bold text-white">{{ authStore.user?.favorites ?? 0 }}</div>
                  <div class="text-xs text-gray-500 uppercase tracking-wider">Favoritos</div>
                </div>
                <div class="text-center">
                  <div class="text-xl font-bold text-white">{{ authStore.user?.following ?? 0 }}</div>
                  <div class="text-xs text-gray-500 uppercase tracking-wider">Siguiendo</div>
                </div>
              </div>
            </div>
          </div>
        </section>

        <MangaContinueReading v-if="continueReading.length" :items="continueReading" />
        <MangaCarousel v-if="recommended.length" title="⭐ Recomendados para ti" :items="recommended" />
        <MangaCarousel title="🔥 Tendencias" :items="trending" />
        <MangaList title="🆕 Últimos capítulos" :items="latestChapters" />

      </div>

    </template>
  </div>
</template>

<script setup lang="ts">
import { rankColors, rankGlow } from '@/helpers/utils';
import { formatReaders } from '@/helpers';
import { ref, onMounted }  from 'vue';
import { RouterLink }      from 'vue-router';
import { useAuthStore } from '@/stores/Auth';
import MangaCarousel        from '@/components/MangaCarousel.vue';
import MangaList            from '@/components/MangaList.vue';
import MangaContinueReading from '@/components/MangaContinueReading.vue';
import { getHomePage } from '@/repositories/HomeService';
import type {
  Manga,
  PopularManga,
  ChapterVersion,
  ContinueReading,
} from '@/types/Manga';

const authStore = useAuthStore();
const loading  = ref(true);
const error    = ref<string | null>(null);

const trending        = ref<Manga[]>([]);
const popular         = ref<PopularManga[]>([]);
const latestChapters  = ref<ChapterVersion[]>([]);
const continueReading = ref<ContinueReading[]>([]);
const recommended     = ref<Manga[]>([]);

const stats = [
  { value: '10K+',  label: 'Títulos'   },
  { value: '500K+', label: 'Capítulos' },
  { value: '50K+',  label: 'Usuarios'  },
];

const genres = [
  { name: 'Acción',   emoji: '⚔️', slug: 'action',  bg: 'bg-gradient-to-br from-red-900/60 to-orange-900/40'    },
  { name: 'Romance',  emoji: '💕', slug: 'romance', bg: 'bg-gradient-to-br from-pink-900/60 to-purple-900/40'   },
  { name: 'Drama',    emoji: '🎭', slug: 'drama',   bg: 'bg-gradient-to-br from-blue-900/60 to-cyan-900/40'     },
  { name: 'Comedia',  emoji: '😂', slug: 'comedy',  bg: 'bg-gradient-to-br from-green-900/60 to-emerald-900/40' },
  { name: 'Fantasía', emoji: '✨', slug: 'fantasy', bg: 'bg-gradient-to-br from-purple-900/60 to-indigo-900/40' },
  { name: 'Sci-Fi',   emoji: '🔮', slug: 'sci-fi',  bg: 'bg-gradient-to-br from-slate-800/80 to-gray-900/60'    },
];

const loadHomePage = async (): Promise<void> => {
  loading.value = true;
  error.value = null;
  try {
    const response = await getHomePage();
    trending.value = response?.trending;
    popular.value = response?.popular;
    latestChapters.value = response?.latestChapters || [];

    if (response.user) {
      authStore.hydrateFromHome(response?.user);
      continueReading.value = response?.continueReading || [];
      recommended.value = response?.recommended || [];
    }
  } catch (e) {
    error.value = `No se pudo cargar el contenido. Intenta de nuevo. ${e}`;
  } finally {
    loading.value = false;
  }
};

onMounted(async () => {
  await loadHomePage();
});
</script>
