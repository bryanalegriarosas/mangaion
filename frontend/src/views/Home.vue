<template>
  <div class="min-h-screen bg-gray-950 text-white">

    <!-- LOADING -->
    <div v-if="loading" class="p-10 text-center text-gray-400">
      Cargando...
    </div>

    <template v-else>

      <!-- ================= -->
      <!-- 🔓 GUEST VERSION -->
      <!-- ================= -->
      <div v-if="!isAuth">

        <!-- HERO -->
        <section class="text-center py-20 px-4">
          <h1 class="text-4xl md:text-5xl font-bold">
            Lee y comparte manga
          </h1>
          <p class="mt-4 text-gray-400 text-lg">
            Miles de historias te esperan
          </p>

          <div class="mt-6 flex justify-center gap-4">
            <button class="bg-indigo-600 hover:bg-indigo-500 px-6 py-3 rounded-xl font-semibold">
              Registrarse
            </button>
            <button class="border border-gray-600 px-6 py-3 rounded-xl hover:bg-gray-800">
              Explorar
            </button>
          </div>
        </section>

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

        <!-- CTA -->
        <section class="text-center py-16">
          <h2 class="text-2xl font-bold">
            ¿Quieres subir tu manga?
          </h2>
          <button class="mt-4 bg-indigo-600 px-6 py-3 rounded-xl">
            Crear cuenta
          </button>
        </section>

      </div>

      <!-- ================= -->
      <!-- 🔐 AUTH VERSION -->
      <!-- ================= -->
      <div v-else class="space-y-10">

        <!-- BIENVENIDA -->
        <section class="px-6 pt-10">
          <h1 class="text-2xl font-bold">
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
