<template>
  <div class="min-h-screen transition-colors duration-300
              bg-gray-50 dark:bg-[#0d0d12]
              text-gray-900 dark:text-gray-100">

    <!-- ===================== -->
    <!-- 💀 SKELETON           -->
    <!-- ===================== -->
    <template v-if="loading">
      <div class="px-6 py-12 max-w-7xl mx-auto space-y-8 animate-pulse">
        <div class="h-24 rounded-2xl bg-gray-200 dark:bg-gray-800/50"></div>
        <div class="grid grid-cols-2 md:grid-cols-4 gap-4">
          <div v-for="i in 4" :key="i" class="h-28 rounded-xl bg-gray-200 dark:bg-gray-800/50"></div>
        </div>
        <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
          <div class="lg:col-span-2 h-80 rounded-xl bg-gray-200 dark:bg-gray-800/50"></div>
          <div class="h-80 rounded-xl bg-gray-200 dark:bg-gray-800/50"></div>
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
        <button
          class="mt-2 bg-red-500 hover:bg-red-400 px-6 py-3 rounded-xl text-white font-semibold transition-colors"
          @click="loadDashboard()"
        >Reintentar</button>
      </div>
    </template>

    <!-- ===================== -->
    <!-- ✅ DASHBOARD CONTENT  -->
    <!-- ===================== -->
    <template v-else>
      <div class="max-w-7xl mx-auto px-6 py-10 space-y-8">

        <!-- ─── HEADER ──────────────────────────────────────────── -->
        <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4">
          <div>
            <p class="text-xs font-semibold uppercase tracking-[0.2em] text-gray-400 mb-1">Panel de control</p>
            <h1 class="text-3xl font-black text-gray-900 dark:text-white">
              {{ store.user?.username ?? store.user?.name }}<span class="text-red-500 dark:text-red-400">.</span>
            </h1>
          </div>
          <!-- Tabs de navegación -->
          <div class="flex gap-1 p-1 rounded-xl bg-gray-100 dark:bg-gray-900 border border-gray-200 dark:border-white/10">
            <button
              v-for="tab in tabs"
              :key="tab.id"
              @click="activeTab = tab.id"
              :class="activeTab === tab.id
                ? 'bg-white dark:bg-gray-800 text-gray-900 dark:text-white shadow-sm'
                : 'text-gray-500 hover:text-gray-700 dark:hover:text-gray-300'"
              class="px-4 py-2 rounded-lg text-sm font-semibold transition-all duration-150"
            >
              {{ tab.label }}
            </button>
          </div>
        </div>

        <!-- ─── STATS ────────────────────────────────────────────── -->
        <div class="grid grid-cols-2 md:grid-cols-4 gap-4">
          <div
            v-for="stat in dashboardStats"
            :key="stat.label"
            class="p-5 rounded-xl border transition-all duration-200
                   bg-white dark:bg-gray-900
                   border-gray-200 dark:border-white/10"
          >
            <div class="text-2xl mb-2">{{ stat.icon }}</div>
            <div class="text-2xl font-black text-gray-900 dark:text-white">{{ stat.value }}</div>
            <div class="text-xs text-gray-500 uppercase tracking-wider mt-1">{{ stat.label }}</div>
          </div>
        </div>

        <!-- ══════════════════════════════════════════════════════ -->
        <!-- TAB: GENERAL                                          -->
        <!-- ══════════════════════════════════════════════════════ -->
        <template v-if="activeTab === 'general'">

          <!-- Acciones rápidas -->
          <div>
            <h2 class="text-sm font-semibold uppercase tracking-[0.2em] text-gray-400 mb-4">Acciones rápidas</h2>
            <div class="grid grid-cols-2 md:grid-cols-4 gap-3">
              <button
                v-for="action in quickActions"
                :key="action.label"
                @click="action.handler()"
                class="group flex flex-col items-start gap-3 p-5 rounded-xl border text-left
                       transition-all duration-200
                       bg-white dark:bg-gray-900
                       border-gray-200 dark:border-white/10
                       hover:border-red-400 dark:hover:border-red-500
                       hover:shadow-lg hover:shadow-red-500/10"
              >
                <span class="text-2xl">{{ action.icon }}</span>
                <div>
                  <div class="font-bold text-sm text-gray-900 dark:text-white
                              group-hover:text-red-500 dark:group-hover:text-red-400 transition-colors">
                    {{ action.label }}
                  </div>
                  <div class="text-xs text-gray-500 mt-0.5">{{ action.description }}</div>
                </div>
              </button>
            </div>
          </div>

          <!-- Continuar leyendo -->
          <div v-if="continueReading.length">
            <div class="flex items-center justify-between mb-4">
              <h2 class="text-sm font-semibold uppercase tracking-[0.2em] text-gray-400">Continuar leyendo</h2>
              <RouterLink to="/reading-history" class="text-xs text-red-500 dark:text-red-400 hover:text-red-400 font-medium">
                Ver todo →
              </RouterLink>
            </div>
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-4">
              <RouterLink
                v-for="item in continueReading.slice(0, 6)"
                :key="item.chapter.id"
                :to="`/read/${item.chapter.slug}`"
                class="group flex gap-4 p-4 rounded-xl border transition-all duration-200
                       bg-white dark:bg-gray-900
                       border-gray-200 dark:border-white/10
                       hover:border-red-400 dark:hover:border-red-500
                       hover:shadow-lg hover:shadow-red-500/10"
              >
                <img
                  :src="item.manga.cover ?? '/placeholder-cover.jpg'"
                  :alt="item.manga.title"
                  class="w-14 h-18 object-cover rounded-lg flex-shrink-0 bg-gray-100 dark:bg-gray-800"
                >
                <div class="flex-1 min-w-0">
                  <h3 class="font-bold text-sm line-clamp-1 text-gray-900 dark:text-white mb-0.5">
                    {{ item.manga.title }}
                  </h3>
                  <p class="text-xs text-gray-500 mb-2">Cap. {{ item.chapter.chapter_number }}</p>
                  <div class="w-full bg-gray-100 dark:bg-gray-700 rounded-full h-1.5">
                    <div
                      class="bg-red-500 h-1.5 rounded-full transition-all duration-300"
                      :style="{ width: `${item.progress}%` }"
                    ></div>
                  </div>
                  <p class="text-xs mt-1 text-gray-400">{{ item.progress }}% completado</p>
                </div>
              </RouterLink>
            </div>
          </div>

          <!-- Sin historial -->
          <div v-else class="flex flex-col items-center justify-center py-16 rounded-xl border border-dashed
                             border-gray-200 dark:border-white/10 text-center">
            <p class="text-4xl mb-3">📖</p>
            <p class="font-semibold text-gray-700 dark:text-gray-300">No has empezado a leer nada aún</p>
            <RouterLink to="/catalog" class="mt-3 text-sm text-red-500 dark:text-red-400 hover:underline">
              Explorar catálogo →
            </RouterLink>
          </div>

        </template>

        <!-- ══════════════════════════════════════════════════════ -->
        <!-- TAB: MIS MANGAS                                       -->
        <!-- ══════════════════════════════════════════════════════ -->
        <template v-else-if="activeTab === 'mangas'">

          <div class="flex items-center justify-between">
            <h2 class="text-sm font-semibold uppercase tracking-[0.2em] text-gray-400">Mis mangas</h2>
            <RouterLink to="/mangas/create">
              <button class="flex items-center gap-2 bg-red-500 hover:bg-red-400 text-white
                             px-4 py-2 rounded-xl text-sm font-bold transition-colors">
                <span>+</span> Nuevo manga
              </button>
            </RouterLink>
          </div>

          <!-- Lista de mangas del usuario -->
          <div v-if="myMangas.length" class="space-y-3">
            <div
              v-for="manga in myMangas"
              :key="manga.id"
              class="flex items-center gap-4 p-4 rounded-xl border transition-all duration-200
                     bg-white dark:bg-gray-900
                     border-gray-200 dark:border-white/10
                     hover:border-gray-300 dark:hover:border-white/20"
            >
              <img
                :src="manga.cover ?? '/placeholder-cover.jpg'"
                :alt="manga.title"
                class="w-12 h-16 object-cover rounded-lg flex-shrink-0 bg-gray-100 dark:bg-gray-800"
              >
              <div class="flex-1 min-w-0">
                <h3 class="font-bold text-gray-900 dark:text-white line-clamp-1">{{ manga.title }}</h3>
                <div class="flex items-center gap-3 mt-1">
                  <span :class="statusColors[manga.status]" class="text-xs font-semibold px-2 py-0.5 rounded-full">
                    {{ statusLabels[manga.status] }}
                  </span>
                  <span class="text-xs text-gray-500">{{ manga.type }}</span>
                  <span v-if="manga.rating" class="text-xs text-gray-500">⭐ {{ manga.rating }}</span>
                </div>
              </div>
              <div class="flex items-center gap-2 flex-shrink-0">
                <RouterLink :to="`/mangas/${manga.id}/chapters/create`">
                  <button class="px-3 py-1.5 rounded-lg text-xs font-semibold transition-colors
                                 bg-gray-100 dark:bg-gray-800
                                 text-gray-700 dark:text-gray-300
                                 hover:bg-gray-200 dark:hover:bg-gray-700">
                    + Capítulo
                  </button>
                </RouterLink>
                <RouterLink :to="`/mangas/${manga.id}/edit`">
                  <button class="px-3 py-1.5 rounded-lg text-xs font-semibold transition-colors
                                 bg-gray-100 dark:bg-gray-800
                                 text-gray-700 dark:text-gray-300
                                 hover:bg-gray-200 dark:hover:bg-gray-700">
                    Editar
                  </button>
                </RouterLink>
                <button
                  @click="confirmDelete(manga)"
                  class="px-3 py-1.5 rounded-lg text-xs font-semibold transition-colors
                         bg-red-50 dark:bg-red-500/10
                         text-red-600 dark:text-red-400
                         hover:bg-red-100 dark:hover:bg-red-500/20"
                >
                  Eliminar
                </button>
              </div>
            </div>
          </div>

          <!-- Sin mangas -->
          <div v-else class="flex flex-col items-center justify-center py-20 rounded-xl border border-dashed
                             border-gray-200 dark:border-white/10 text-center">
            <p class="text-5xl mb-4">📚</p>
            <p class="font-bold text-lg text-gray-700 dark:text-gray-300 mb-1">
              No tienes mangas publicados
            </p>
            <p class="text-sm text-gray-500 mb-6">Empieza subiendo tu primera historia</p>
            <RouterLink to="/mangas/create">
              <button class="bg-red-500 hover:bg-red-400 text-white px-6 py-3 rounded-xl font-bold transition-colors">
                Crear mi primer manga
              </button>
            </RouterLink>
          </div>

        </template>

        <!-- ══════════════════════════════════════════════════════ -->
        <!-- TAB: CAPÍTULOS                                        -->
        <!-- ══════════════════════════════════════════════════════ -->
        <template v-else-if="activeTab === 'chapters'">

          <div class="flex items-center justify-between">
            <h2 class="text-sm font-semibold uppercase tracking-[0.2em] text-gray-400">Capítulos recientes</h2>
            <!-- Selector de manga para filtrar -->
            <select
              v-model="selectedMangaFilter"
              class="px-3 py-2 rounded-xl text-sm border transition-colors
                     bg-white dark:bg-gray-900
                     border-gray-200 dark:border-white/10
                     text-gray-700 dark:text-gray-300
                     focus:outline-none focus:border-red-400"
            >
              <option value="">Todos mis mangas</option>
              <option v-for="manga in myMangas" :key="manga.id" :value="manga.id">
                {{ manga.title }}
              </option>
            </select>
          </div>

          <div v-if="recentChapters.length" class="space-y-3">
            <div
              v-for="chapter in recentChapters"
              :key="chapter.id"
              class="flex items-center gap-4 p-4 rounded-xl border transition-all duration-200
                     bg-white dark:bg-gray-900
                     border-gray-200 dark:border-white/10"
            >
              <div class="w-10 h-10 flex items-center justify-center rounded-lg flex-shrink-0
                          bg-gray-100 dark:bg-gray-800 text-sm font-black text-gray-700 dark:text-gray-300">
                {{ chapter.chapter_number }}
              </div>
              <div class="flex-1 min-w-0">
                <h3 class="font-bold text-sm text-gray-900 dark:text-white line-clamp-1">
                  {{ chapter.manga.title }}
                </h3>
                <div class="flex items-center gap-3 mt-0.5">
                  <span class="text-xs text-gray-500">{{ chapter.title ?? `Capítulo ${chapter.chapter_number}` }}</span>
                  <span class="text-xs text-gray-400">
                    {{ chapter.language.code.toUpperCase() }}
                  </span>
                  <span class="text-xs text-gray-400">{{ chapter.scan_group.name }}</span>
                  <span v-if="chapter.published_at" class="text-xs text-gray-400">
                    {{ formatDate(chapter.published_at) }}
                  </span>
                </div>
              </div>
              <div class="flex items-center gap-2 flex-shrink-0">
                <RouterLink :to="`/read/${chapter.slug}`">
                  <button class="px-3 py-1.5 rounded-lg text-xs font-semibold transition-colors
                                 bg-gray-100 dark:bg-gray-800
                                 text-gray-700 dark:text-gray-300
                                 hover:bg-gray-200 dark:hover:bg-gray-700">
                    Ver
                  </button>
                </RouterLink>
                <button 
                  @click="confirmDeleteChapter(chapter)"
                  class="px-3 py-1.5 rounded-lg text-xs font-semibold transition-colors
                               bg-red-50 dark:bg-red-500/10
                               text-red-600 dark:text-red-400
                               hover:bg-red-100 dark:hover:bg-red-500/20">
                  Eliminar
                </button>
              </div>
            </div>
          </div>

          <div v-else class="flex flex-col items-center justify-center py-20 rounded-xl border border-dashed
                             border-gray-200 dark:border-white/10 text-center">
            <p class="text-5xl mb-4">📄</p>
            <p class="font-bold text-lg text-gray-700 dark:text-gray-300 mb-1">Sin capítulos aún</p>
            <p class="text-sm text-gray-500">Selecciona un manga y sube tu primer capítulo</p>
          </div>

        </template>

        <!-- ══════════════════════════════════════════════════════ -->
        <!-- TAB: SCAN GROUPS                                      -->
        <!-- ══════════════════════════════════════════════════════ -->
        <template v-else-if="activeTab === 'groups'">

          <div class="flex items-center justify-between">
            <h2 class="text-sm font-semibold uppercase tracking-[0.2em] text-gray-400">Grupos de escaneo</h2>
            <RouterLink to="/scan-groups/create">
              <button class="flex items-center gap-2 bg-red-500 hover:bg-red-400 text-white
                             px-4 py-2 rounded-xl text-sm font-bold transition-colors">
                <span>+</span> Nuevo grupo
              </button>
            </RouterLink>
          </div>

          <div v-if="scanGroups.length" class="grid grid-cols-1 md:grid-cols-2 gap-4">
            <div
              v-for="group in scanGroups"
              :key="group.id"
              class="p-5 rounded-xl border transition-all duration-200
                     bg-white dark:bg-gray-900
                     border-gray-200 dark:border-white/10
                     hover:border-gray-300 dark:hover:border-white/20"
            >
              <!-- Header del grupo -->
              <div class="flex items-start justify-between mb-4">
                <div>
                  <h3 class="font-black text-gray-900 dark:text-white">{{ group.name }}</h3>
                  <a
                    v-if="group.website"
                    :href="group.website"
                    target="_blank"
                    rel="noopener"
                    class="text-xs text-red-500 dark:text-red-400 hover:underline"
                  >
                    {{ group.website }}
                  </a>
                </div>
                <span :class="groupRoleColors[group.role ?? 'member']"
                      class="text-xs font-bold px-2 py-1 rounded-full">
                  {{ groupRoleLabels[group.role ?? 'member'] }}
                </span>
              </div>

              <!-- Miembros -->
              <div class="mb-4">
                <p class="text-xs text-gray-400 uppercase tracking-wider mb-2">
                  Miembros ({{ group.members?.length ?? 0 }})
                </p>
                <div class="flex flex-wrap gap-2">
                  <div
                    v-for="member in (group.members ?? []).slice(0, 5)"
                    :key="member.id"
                    class="flex items-center gap-1.5 px-2 py-1 rounded-lg
                           bg-gray-50 dark:bg-gray-800
                           border border-gray-100 dark:border-white/5"
                  >
                    <img
                      v-if="member.avatar"
                      :src="member.avatar"
                      :alt="member.name"
                      class="w-4 h-4 rounded-full object-cover"
                    >
                    <div v-else class="w-4 h-4 rounded-full bg-red-400 flex items-center justify-center text-[8px] text-white font-bold">
                      {{ member.name.charAt(0).toUpperCase() }}
                    </div>
                    <span class="text-xs text-gray-700 dark:text-gray-300">{{ member.name }}</span>
                  </div>
                  <div
                    v-if="(group.members?.length ?? 0) > 5"
                    class="px-2 py-1 rounded-lg text-xs text-gray-400
                           bg-gray-50 dark:bg-gray-800
                           border border-gray-100 dark:border-white/5"
                  >
                    +{{ (group.members?.length ?? 0) - 5 }} más
                  </div>
                </div>
              </div>

              <!-- Acciones -->
              <div class="flex gap-2 pt-3 border-t border-gray-100 dark:border-white/5">
                <RouterLink :to="`/scan-groups/${group.id}`" class="flex-1">
                  <button class="w-full px-3 py-2 rounded-lg text-xs font-semibold transition-colors
                                 bg-gray-100 dark:bg-gray-800
                                 text-gray-700 dark:text-gray-300
                                 hover:bg-gray-200 dark:hover:bg-gray-700">
                    Ver grupo
                  </button>
                </RouterLink>
                <RouterLink
                  v-if="group.role === 'owner'"
                  :to="`/scan-groups/${group.id}/edit`"
                  class="flex-1"
                >
                  <button class="w-full px-3 py-2 rounded-lg text-xs font-semibold transition-colors
                                 bg-gray-100 dark:bg-gray-800
                                 text-gray-700 dark:text-gray-300
                                 hover:bg-gray-200 dark:hover:bg-gray-700">
                    Gestionar
                  </button>
                </RouterLink>
              </div>
            </div>
          </div>

          <div v-else class="flex flex-col items-center justify-center py-20 rounded-xl border border-dashed
                             border-gray-200 dark:border-white/10 text-center">
            <p class="text-5xl mb-4">👥</p>
            <p class="font-bold text-lg text-gray-700 dark:text-gray-300 mb-1">
              No perteneces a ningún grupo
            </p>
            <p class="text-sm text-gray-500 mb-6">Crea un grupo de escaneo o únete a uno existente</p>
            <RouterLink to="/scan-groups/create">
              <button class="bg-red-500 hover:bg-red-400 text-white px-6 py-3 rounded-xl font-bold transition-colors">
                Crear grupo
              </button>
            </RouterLink>
          </div>

        </template>

      </div><!-- /max-w-7xl -->
    </template>

  </div>
</template>

<script setup lang="ts">
import { ref, computed, onMounted } from 'vue'
import { RouterLink, useRouter }    from 'vue-router'
import { useAuthStore }             from '@/stores/Auth'
import { getHomePage }              from '@/repositories/HomeService'
import Api                          from '@/services/Api'
import type { Manga, ContinueReading, ChapterVersion } from '@/types/Manga'
import alertService                 from '@/helpers/sweetalert'

// ─── Types locales ────────────────────────────────────────────────
interface ScanGroupMember {
  id:     number
  name:   string
  avatar: string | null
}

interface ScanGroup {
  id:      number
  name:    string
  website: string | null
  role:    'owner' | 'uploader' | 'editor' | null
  members: ScanGroupMember[]
}

// ─── Stores / Router ──────────────────────────────────────────────
const store  = useAuthStore()
const router = useRouter()

// ─── State ────────────────────────────────────────────────────────
const loading             = ref(true)
const error               = ref<string | null>(null)
const activeTab           = ref<'general' | 'mangas' | 'chapters' | 'groups'>('general')
const continueReading     = ref<ContinueReading[]>([])
const myMangas            = ref<Manga[]>([])
const recentChapters      = ref<ChapterVersion[]>([])
const scanGroups          = ref<ScanGroup[]>([])
const selectedMangaFilter = ref<number | ''>('')

// ─── Tabs ─────────────────────────────────────────────────────────
const tabs = [
  { id: 'general',  label: '🏠 General'   },
  { id: 'mangas',   label: '📚 Mangas'    },
  { id: 'chapters', label: '📄 Capítulos' },
  { id: 'groups',   label: '👥 Grupos'    },
] as const

// ─── Quick actions ────────────────────────────────────────────────
const quickActions = computed(() => [
  {
    icon: '📚', label: 'Nuevo manga', description: 'Publicar una nueva historia',
    handler: () => router.push('/mangas/create'),
  },
  {
    icon: '📄', label: 'Subir capítulo', description: 'Agregar un nuevo capítulo',
    handler: () => { activeTab.value = 'mangas' },
  },
  {
    icon: '👥', label: 'Crear grupo', description: 'Formar un grupo de scan',
    handler: () => router.push('/scan-groups/create'),
  },
  {
    icon: '❤️', label: 'Mis favoritos', description: 'Ver mangas guardados',
    handler: () => router.push('/favorites'),
  },
])

// ─── Stats del dashboard ──────────────────────────────────────────
const dashboardStats = computed(() => [
  { icon: '📚', label: 'Mis mangas',    value: myMangas.value.length         },
  { icon: '📄', label: 'Capítulos',     value: recentChapters.value.length   },
  { icon: '👥', label: 'Grupos',        value: scanGroups.value.length       },
  { icon: '❤️', label: 'Favoritos',     value: store.user?.favorites ?? 0    },
])

// ─── Estilos por estado/rol ───────────────────────────────────────
const statusColors: Record<string, string> = {
  ongoing:   'bg-green-100 text-green-700 dark:bg-green-500/15 dark:text-green-400',
  completed: 'bg-blue-100 text-blue-700 dark:bg-blue-500/15 dark:text-blue-400',
  hiatus:    'bg-yellow-100 text-yellow-700 dark:bg-yellow-500/15 dark:text-yellow-400',
  cancelled: 'bg-red-100 text-red-700 dark:bg-red-500/15 dark:text-red-400',
}
const statusLabels: Record<string, string> = {
  ongoing: 'En curso', completed: 'Completado', hiatus: 'En pausa', cancelled: 'Cancelado',
}
const groupRoleColors: Record<string, string> = {
  owner:    'bg-red-100 text-red-700 dark:bg-red-500/15 dark:text-red-400',
  uploader: 'bg-blue-100 text-blue-700 dark:bg-blue-500/15 dark:text-blue-400',
  editor:   'bg-purple-100 text-purple-700 dark:bg-purple-500/15 dark:text-purple-400',
  member:   'bg-gray-100 text-gray-700 dark:bg-gray-700 dark:text-gray-300',
}
const groupRoleLabels: Record<string, string> = {
  owner: 'Propietario', uploader: 'Uploader', editor: 'Editor', member: 'Miembro',
}

// ─── Helpers ──────────────────────────────────────────────────────
function formatDate(iso: string): string {
  return new Date(iso).toLocaleDateString('es-MX', {
    day: 'numeric', month: 'short', year: 'numeric',
  })
}

async function confirmDelete(manga: Manga): Promise<void> {
  const result = await alertService.confirm(
    '¿Eliminar manga?',
    `Estás a punto de eliminar <strong>${manga.title}</strong>. Esta acción eliminará también todos sus capítulos y páginas. No se puede deshacer.`,
    {
      confirmButtonText: 'Sí, eliminar',
      cancelButtonText: 'Cancelar',
      icon: 'warning',
    }
  )

  if (result.isConfirmed) {
    await deleteManga(manga)
  }
}

async function deleteManga(manga: Manga): Promise<void> {
  try {
    // Mostrar alerta de carga
    alertService.loading('Eliminando...', 'Procesando tu solicitud')
    
    await Api.delete(`/mangas/${manga.id}`)
    
    // Cerrar alerta de carga
    alertService.close()
    
    // Eliminar manga de la lista
    myMangas.value = myMangas.value.filter(m => m.id !== manga.id)
    
    // Mostrar alerta de éxito
    await alertService.success('Manga eliminado', `${manga.title} ha sido eliminado correctamente`)
    
    // También mostrar toast de éxito
    alertService.toast.success('Manga eliminado correctamente')
    
  } catch (error: any) {
    alertService.close()
    console.error('[Dashboard] Error deleting manga:', error)
    
    if (error.response?.status === 403) {
      await alertService.error('Permiso denegado', 'No tienes permisos para eliminar este manga')
    } else if (error.response?.status === 404) {
      await alertService.error('Manga no encontrado', 'El manga que intentas eliminar no existe')
    } else {
      await alertService.error('Error', 'No se pudo eliminar el manga. Intenta de nuevo.')
    }
  }
}

async function confirmDeleteChapter(chapter: ChapterVersion): Promise<void> {
  const result = await alertService.confirm(
    '¿Eliminar capítulo?',
    `Estás a punto de eliminar el <strong>Capítulo ${chapter.chapter_number}</strong> de <strong>${chapter.manga.title}</strong>. Esta acción no se puede deshacer.`,
    {
      confirmButtonText: 'Sí, eliminar',
      cancelButtonText: 'Cancelar',
      icon: 'warning',
    }
  )

  if (result.isConfirmed) {
    await deleteChapter(chapter)
  }
}

async function deleteChapter(chapter: ChapterVersion): Promise<void> {
  try {
    // Mostrar alerta de carga
    alertService.loading('Eliminando...', 'Procesando tu solicitud')
    
    await Api.delete(`/chapter-versions/${chapter.id}`)
    
    // Cerrar alerta de carga
    alertService.close()
    
    // Eliminar capítulo de la lista
    recentChapters.value = recentChapters.value.filter(c => c.id !== chapter.id)
    
    // Mostrar alerta de éxito
    await alertService.success('Capítulo eliminado', `El capítulo ha sido eliminado correctamente`)
    
    // También mostrar toast de éxito
    alertService.toast.success('Capítulo eliminado correctamente')
    
  } catch (error: any) {
    alertService.close()
    console.error('[Dashboard] Error deleting chapter:', error)
    
    if (error.response?.status === 403) {
      await alertService.error('Permiso denegado', 'No tienes permisos para eliminar este capítulo')
    } else if (error.response?.status === 404) {
      await alertService.error('Capítulo no encontrado', 'El capítulo que intentas eliminar no existe')
    } else {
      await alertService.error('Error', 'No se pudo eliminar el capítulo. Intenta de nuevo.')
    }
  }
}

// ─── Data fetching ────────────────────────────────────────────────
async function loadDashboard(): Promise<void> {
  loading.value = true
  error.value   = null
  try {
    // Llamadas en paralelo para no bloquear //groupsRes
    const [homeRes, mangasRes] = await Promise.all([
      getHomePage(),
      Api.get('/mangas/mine').catch(() => ({ data: { data: [] } })),
      //Api.get('/scan-groups/mine').catch(() => ({ data: [] })),
    ])

    if (homeRes.user) {
      store.hydrateFromHome(homeRes.user)
      continueReading.value = homeRes.continueReading ?? []
    }

    myMangas.value  = mangasRes.data?.data  ?? []
    //scanGroups.value = groupsRes.data       ?? []

    // Capítulos recientes de los mangas del usuario
    /*if (myMangas.value.length) {
      const chaptersRes = await Api.get('/chapter-versions/mine').catch(() => ({ data: [] }))
      recentChapters.value = chaptersRes.data ?? []
    }*/

  } catch (e) {
    error.value = 'No se pudo cargar el dashboard. Intenta de nuevo.'
    console.error('[Dashboard] error:', e)
  } finally {
    loading.value = false
  }
};

onMounted(() => {
  loadDashboard();
});
</script>
