<template>
  <div class="text-gray-900">

    <!-- LOADING -->
    <div v-if="loading" class="p-10 text-center text-gray-600">
      Cargando...
    </div>

    <template v-else>

      <!-- ================= -->
      <!-- 🔓 GUEST VERSION -->
      <!-- ================= -->
      <div v-if="!isAuth">

        <!-- HERO -->
        <section class="relative overflow-hidden bg-gray-100">
          <!-- Background gradient -->
          <div class="absolute inset-0 bg-gradient-to-br from-gray-100 via-gray-50 to-slate-100"></div>
          
          <!-- Floating elements -->
          <div class="absolute inset-0">
            <div class="absolute top-20 left-10 w-20 h-20 bg-slate-200/20 rounded-full blur-xl animate-pulse"></div>
            <div class="absolute top-40 right-20 w-32 h-32 bg-gray-200/20 rounded-full blur-xl animate-pulse delay-1000"></div>
            <div class="absolute bottom-20 left-1/4 w-24 h-24 bg-blue-200/20 rounded-full blur-xl animate-pulse delay-2000"></div>
          </div>

          <div class="relative px-6 py-24 md:py-32 text-center">
            <div class="max-w-4xl mx-auto">
              <!-- Badge -->
              <div class="inline-flex items-center gap-2 bg-gray-200 border border-gray-300 px-4 py-2 rounded-full text-gray-700 text-sm font-medium mb-6">
                <span class="w-2 h-2 bg-gray-500 rounded-full animate-pulse"></span>
                Nueva temporada disponible
              </div>

              <!-- Main title -->
              <h1 class="text-5xl md:text-7xl font-bold bg-gradient-to-r from-slate-700 via-gray-700 to-slate-800 bg-clip-text text-transparent mb-6">
                Lee y comparte
                <br>
                <span class="text-6xl md:text-8xl">manga</span>
              </h1>
              
              <!-- Subtitle -->
              <p class="text-xl md:text-2xl text-gray-600 mb-8 max-w-2xl mx-auto leading-relaxed">
                Miles de historias te esperan en la plataforma más grande de manga en español
              </p>

              <!-- Stats -->
              <div class="flex flex-wrap justify-center gap-8 mb-10 text-gray-500">
                <div class="text-center">
                  <div class="text-3xl font-bold text-slate-700">10K+</div>
                  <div class="text-sm">Títulos</div>
                </div>
                <div class="text-center">
                  <div class="text-3xl font-bold text-gray-700">500K+</div>
                  <div class="text-sm">Capítulos</div>
                </div>
                <div class="text-center">
                  <div class="text-3xl font-bold text-slate-600">50K+</div>
                  <div class="text-sm">Usuarios</div>
                </div>
              </div>

              <!-- CTA Buttons -->
              <div class="flex flex-col sm:flex-row justify-center gap-4">
                <button class="bg-gradient-to-r from-slate-700 to-gray-700 hover:from-slate-800 hover:to-gray-800 px-8 py-4 rounded-xl font-semibold text-lg shadow-lg hover:shadow-slate-500/25 transform hover:scale-105 transition-all duration-200 text-white">
                  <span class="flex items-center gap-2">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                      <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"></path>
                    </svg>
                    Registrarse gratis
                  </span>
                </button>
                <button class="border border-gray-400 hover:border-gray-500 px-8 py-4 rounded-xl font-semibold text-lg bg-gray-50/80 hover:bg-gray-100 transform hover:scale-105 transition-all duration-200">
                  <span class="flex items-center gap-2">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                      <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"></path>
                    </svg>
                    Explorar catálogo
                  </span>
                </button>
              </div>
            </div>
          </div>
        </section>

        <!-- CATEGORIES -->
        <section class="px-6 py-12 bg-gray-100/50 backdrop-blur-sm">
          <div class="max-w-6xl mx-auto">
            <h2 class="text-2xl font-bold mb-8 text-center text-gray-800">Explora por género</h2>
            <div class="grid grid-cols-2 sm:grid-cols-3 md:grid-cols-4 lg:grid-cols-6 gap-4">
              <div class="group cursor-pointer">
                <div class="bg-gradient-to-br from-red-100 to-orange-100 border border-red-200 rounded-xl p-4 text-center hover:from-red-200 hover:to-orange-200 transition-all duration-200 group-hover:scale-105 shadow-sm hover:shadow-md">
                  <div class="text-2xl mb-2">⚔️</div>
                  <div class="text-sm font-medium text-gray-700">Acción</div>
                </div>
              </div>
              <div class="group cursor-pointer">
                <div class="bg-gradient-to-br from-pink-100 to-purple-100 border border-pink-200 rounded-xl p-4 text-center hover:from-pink-200 hover:to-purple-200 transition-all duration-200 group-hover:scale-105 shadow-sm hover:shadow-md">
                  <div class="text-2xl mb-2">💕</div>
                  <div class="text-sm font-medium text-gray-700">Romance</div>
                </div>
              </div>
              <div class="group cursor-pointer">
                <div class="bg-gradient-to-br from-blue-100 to-cyan-100 border border-blue-200 rounded-xl p-4 text-center hover:from-blue-200 hover:to-cyan-200 transition-all duration-200 group-hover:scale-105 shadow-sm hover:shadow-md">
                  <div class="text-2xl mb-2">🎭</div>
                  <div class="text-sm font-medium text-gray-700">Drama</div>
                </div>
              </div>
              <div class="group cursor-pointer">
                <div class="bg-gradient-to-br from-green-100 to-emerald-100 border border-green-200 rounded-xl p-4 text-center hover:from-green-200 hover:to-emerald-200 transition-all duration-200 group-hover:scale-105 shadow-sm hover:shadow-md">
                  <div class="text-2xl mb-2">😂</div>
                  <div class="text-sm font-medium text-gray-700">Comedia</div>
                </div>
              </div>
              <div class="group cursor-pointer">
                <div class="bg-gradient-to-br from-purple-100 to-indigo-100 border border-purple-200 rounded-xl p-4 text-center hover:from-purple-200 hover:to-indigo-200 transition-all duration-200 group-hover:scale-105 shadow-sm hover:shadow-md">
                  <div class="text-2xl mb-2">✨</div>
                  <div class="text-sm font-medium text-gray-700">Fantasía</div>
                </div>
              </div>
              <div class="group cursor-pointer">
                <div class="bg-gradient-to-br from-gray-100 to-slate-100 border border-gray-200 rounded-xl p-4 text-center hover:from-gray-200 hover:to-slate-200 transition-all duration-200 group-hover:scale-105 shadow-sm hover:shadow-md">
                  <div class="text-2xl mb-2">🔮</div>
                  <div class="text-sm font-medium text-gray-700">Sci-Fi</div>
                </div>
              </div>
            </div>
          </div>
        </section>

        <!-- TRENDING -->
        <MangaCarousel
          title="🔥 Tendencias"
          :items="trending"
        />

        <!-- POPULAR THIS WEEK -->
        <section class="px-6 py-12 bg-gray-100/30 backdrop-blur-sm">
          <div class="max-w-6xl mx-auto">
            <div class="flex items-center justify-between mb-8">
              <h2 class="text-2xl font-bold text-gray-800">📈 Popular esta semana</h2>
              <button class="text-slate-600 hover:text-slate-700 text-sm font-medium">
                Ver todo →
              </button>
            </div>
            
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6">
              <!-- Top 1 -->
              <div class="relative group">
                <div class="absolute -inset-1 bg-gradient-to-r from-purple-400 to-pink-400 rounded-xl blur opacity-25 group-hover:opacity-75 transition duration-300"></div>
                <div class="relative bg-gray-50 border border-gray-200 rounded-xl p-6 shadow-sm">
                  <div class="flex items-start justify-between mb-4">
                    <div class="text-3xl font-bold bg-gradient-to-r from-purple-600 to-pink-600 bg-clip-text text-transparent">#1</div>
                    <div class="bg-green-100 text-green-700 px-2 py-1 rounded-lg text-xs font-semibold">
                      +15%
                    </div>
                  </div>
                  <div class="flex gap-4">
                    <img src="/example.png" class="w-16 h-20 object-cover rounded-lg" alt="Manga">
                    <div class="flex-1">
                      <h3 class="font-semibold mb-1 text-gray-800">One Piece</h3>
                      <p class="text-sm text-gray-600 mb-2">Aventura</p>
                      <div class="flex items-center gap-2 text-xs text-gray-500">
                        <span class="flex items-center gap-1">
                          <svg class="w-3 h-3 fill-current text-yellow-500" viewBox="0 0 20 20">
                            <path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z"/>
                          </svg>
                          4.9
                        </span>
                        <span>1.2M lectores</span>
                      </div>
                    </div>
                  </div>
                </div>
              </div>

              <!-- Top 2 -->
              <div class="relative group">
                <div class="absolute -inset-1 bg-gradient-to-r from-blue-400 to-cyan-400 rounded-xl blur opacity-25 group-hover:opacity-75 transition duration-300"></div>
                <div class="relative bg-gray-50 border border-gray-200 rounded-xl p-6 shadow-sm">
                  <div class="flex items-start justify-between mb-4">
                    <div class="text-3xl font-bold bg-gradient-to-r from-blue-600 to-cyan-600 bg-clip-text text-transparent">#2</div>
                    <div class="bg-green-100 text-green-700 px-2 py-1 rounded-lg text-xs font-semibold">
                      +12%
                    </div>
                  </div>
                  <div class="flex gap-4">
                    <img src="/example.png" class="w-16 h-20 object-cover rounded-lg" alt="Manga">
                    <div class="flex-1">
                      <h3 class="font-semibold mb-1 text-gray-800">Attack on Titan</h3>
                      <p class="text-sm text-gray-600 mb-2">Acción</p>
                      <div class="flex items-center gap-2 text-xs text-gray-500">
                        <span class="flex items-center gap-1">
                          <svg class="w-3 h-3 fill-current text-yellow-500" viewBox="0 0 20 20">
                            <path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z"/>
                          </svg>
                          4.8
                        </span>
                        <span>980K lectores</span>
                      </div>
                    </div>
                  </div>
                </div>
              </div>

              <!-- Top 3 -->
              <div class="relative group">
                <div class="absolute -inset-1 bg-gradient-to-r from-green-400 to-emerald-400 rounded-xl blur opacity-25 group-hover:opacity-75 transition duration-300"></div>
                <div class="relative bg-gray-50 border border-gray-200 rounded-xl p-6 shadow-sm">
                  <div class="flex items-start justify-between mb-4">
                    <div class="text-3xl font-bold bg-gradient-to-r from-green-600 to-emerald-600 bg-clip-text text-transparent">#3</div>
                    <div class="bg-green-100 text-green-700 px-2 py-1 rounded-lg text-xs font-semibold">
                      +8%
                    </div>
                  </div>
                  <div class="flex gap-4">
                    <img src="/example.png" class="w-16 h-20 object-cover rounded-lg" alt="Manga">
                    <div class="flex-1">
                      <h3 class="font-semibold mb-1 text-gray-800">Demon Slayer</h3>
                      <p class="text-sm text-gray-600 mb-2">Acción</p>
                      <div class="flex items-center gap-2 text-xs text-gray-500">
                        <span class="flex items-center gap-1">
                          <svg class="w-3 h-3 fill-current text-yellow-500" viewBox="0 0 20 20">
                            <path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z"/>
                          </svg>
                          4.7
                        </span>
                        <span>750K lectores</span>
                      </div>
                    </div>
                  </div>
                </div>
              </div>

              <!-- Top 4 -->
              <div class="relative group">
                <div class="absolute -inset-1 bg-gradient-to-r from-orange-400 to-red-400 rounded-xl blur opacity-25 group-hover:opacity-75 transition duration-300"></div>
                <div class="relative bg-gray-50 border border-gray-200 rounded-xl p-6 shadow-sm">
                  <div class="flex items-start justify-between mb-4">
                    <div class="text-3xl font-bold bg-gradient-to-r from-orange-600 to-red-600 bg-clip-text text-transparent">#4</div>
                    <div class="bg-green-100 text-green-700 px-2 py-1 rounded-lg text-xs font-semibold">
                      +5%
                    </div>
                  </div>
                  <div class="flex gap-4">
                    <img src="/example.png" class="w-16 h-20 object-cover rounded-lg" alt="Manga">
                    <div class="flex-1">
                      <h3 class="font-semibold mb-1 text-gray-800">My Hero Academia</h3>
                      <p class="text-sm text-gray-600 mb-2">Superhéroes</p>
                      <div class="flex items-center gap-2 text-xs text-gray-500">
                        <span class="flex items-center gap-1">
                          <svg class="w-3 h-3 fill-current text-yellow-500" viewBox="0 0 20 20">
                            <path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z"/>
                          </svg>
                          4.6
                        </span>
                        <span>620K lectores</span>
                      </div>
                    </div>
                  </div>
                </div>
              </div>
            </div>
          </div>
        </section>

        <!-- LATEST -->
        <MangaList
          title="🆕 Últimos capítulos"
          :items="latestChapters"
        />

        <!-- CTA -->
        <section class="text-center py-16 bg-gray-100/40 backdrop-blur-sm">
          <h2 class="text-2xl font-bold text-gray-800">
            ¿Quieres subir tu manga?
          </h2>
          <button class="mt-4 bg-gradient-to-r from-slate-700 to-gray-700 hover:from-slate-800 hover:to-gray-800 px-6 py-3 rounded-xl text-white font-semibold shadow-lg hover:shadow-slate-500/25 transform hover:scale-105 transition-all duration-200">
            Crear cuenta
          </button>
        </section>

      </div>

      <!-- ================= -->
      <!-- 🔐 AUTH VERSION -->
      <!-- ================= -->
      <div v-else class="space-y-10">

        <!-- BIENVENIDA -->
        <section class="px-6 pt-10 bg-gray-100/10 backdrop-blur-sm">
          <h1 class="text-2xl font-bold text-gray-800">
            Bienvenido de nuevo, {{ user.name }}
          </h1>
        </section>

        <!-- CONTINUE READING -->
        <MangaContinueReading
          v-if="continueReading.length"
          :items="continueReading"
        />

        <!-- RECOMMENDED -->
        <MangaCarousel
          v-if="recommended.length"
          title="⭐ Recomendados para ti"
          :items="recommended"
        />

        <!-- TRENDING -->
        <MangaCarousel
          title="🔥 Tendencias"
          :items="trending"
        />

        <!-- LATEST -->
        <MangaList
          title="🆕 Últimos capítulos"
          :items="latestChapters"
        />

      </div>

    </template>
  </div>
</template>

<script setup lang="ts">
import { ref, onMounted, computed } from 'vue'

// Components
import MangaCarousel from '@/components/MangaCarousel.vue';
import MangaList from '@/components/MangaList.vue';
import MangaContinueReading from '@/components/MangaContinueReading.vue';
import Api from '@/services/Api';

// Types
interface Manga {
  id: number
  title: string
  cover: string
}

interface Chapter {
  id: number
  title: string
  manga: Manga
}

interface ContinueReading {
  manga: Manga
  chapter: Chapter
  page: number
}

const user = ref<any>(null)

const trending = ref<Manga[]>([])
const latestChapters = ref<Chapter[]>([])
const continueReading = ref<ContinueReading[]>([])
const recommended = ref<Manga[]>([])

const loading = ref(true)

// Detectar si está autenticado
const isAuth = computed(() => !!user.value)

// Fetch data
const fetchHome = async () => {
  try {
    const { data } = await Api.get('/home')

    trending.value = data.trending
    latestChapters.value = data.latestChapters

    if (data.user) {
      user.value = data.user
      continueReading.value = data.continueReading || []
      recommended.value = data.recommended || []
    }
  } catch (error) {
    console.error(error)
  } finally {
    loading.value = false
  }
}

onMounted(fetchHome)
</script>
