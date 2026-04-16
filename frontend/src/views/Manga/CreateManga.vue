<template>
    <div class="min-h-screen transition-colors duration-300
              bg-gray-50 dark:bg-[#0d0d12]
              text-gray-900 dark:text-gray-100">

        <div class="max-w-4xl mx-auto px-6 py-10">

            <!-- Header -->
            <div class="mb-8">
                <RouterLink to="/dashboard" class="inline-flex items-center gap-2 text-sm text-gray-500 hover:text-gray-700
                 dark:text-gray-400 dark:hover:text-gray-200 transition-colors mb-4">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7" />
                    </svg>
                    Volver al dashboard
                </RouterLink>
                <p class="text-xs font-semibold uppercase tracking-[0.2em] text-gray-400 mb-1">Nuevo manga</p>
                <h1 class="text-3xl font-black text-gray-900 dark:text-white">Publicar manga</h1>
            </div>

            <form @submit.prevent="handleSubmit" novalidate>
                <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">

                    <!-- ── Columna izquierda: Cover ──────────────────────── -->
                    <div class="lg:col-span-1">
                        <label class="block text-xs font-semibold uppercase tracking-[0.2em] text-gray-400 mb-3">
                            Portada <span class="text-red-500">*</span>
                        </label>

                        <!-- Drop zone -->
                        <div @dragover.prevent="isDragging = true" @dragleave.prevent="isDragging = false"
                            @drop.prevent="handleDrop" @click="coverInput?.click()" :class="[
                                'relative cursor-pointer rounded-2xl border-2 border-dashed transition-all duration-200 overflow-hidden',
                                isDragging
                                    ? 'border-red-400 bg-red-50 dark:bg-red-500/10 scale-[1.02]'
                                    : errors.cover_image
                                        ? 'border-red-400 bg-red-50/50 dark:bg-red-500/5'
                                        : 'border-gray-200 dark:border-white/10 hover:border-red-300 dark:hover:border-red-500/50 bg-white dark:bg-gray-900',
                            ]" style="aspect-ratio: 3/4;">
                            <!-- Preview -->
                            <template v-if="coverPreview">
                                <img :src="coverPreview" alt="Preview"
                                    class="absolute inset-0 w-full h-full object-cover">
                                <!-- Overlay al hacer hover -->
                                <div class="absolute inset-0 bg-black/50 opacity-0 hover:opacity-100
                            transition-opacity duration-200 flex flex-col items-center justify-center gap-2">
                                    <svg class="w-8 h-8 text-white" fill="none" stroke="currentColor"
                                        viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                            d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-8l-4-4m0 0L8 8m4-4v12" />
                                    </svg>
                                    <span class="text-white text-sm font-semibold">Cambiar imagen</span>
                                </div>
                            </template>

                            <!-- Placeholder -->
                            <template v-else>
                                <div
                                    class="absolute inset-0 flex flex-col items-center justify-center gap-3 p-6 text-center">
                                    <div class="w-12 h-12 rounded-xl flex items-center justify-center
                              bg-gray-100 dark:bg-gray-800">
                                        <svg class="w-6 h-6 text-gray-400" fill="none" stroke="currentColor"
                                            viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z" />
                                        </svg>
                                    </div>
                                    <div>
                                        <p class="text-sm font-semibold text-gray-700 dark:text-gray-300">
                                            Arrastra tu portada aquí
                                        </p>
                                        <p class="text-xs text-gray-400 mt-1">o haz clic para seleccionar</p>
                                        <p class="text-xs text-gray-400 mt-1">JPG, PNG, WEBP · Máx. 5MB</p>
                                    </div>
                                </div>
                            </template>
                        </div>

                        <!-- Input file oculto -->
                        <input ref="coverInput" type="file" accept="image/jpeg,image/png,image/webp" class="hidden"
                            @change="handleCoverChange">

                        <!-- Error de portada -->
                        <p v-if="errors.cover_image" class="mt-2 text-xs text-red-500">
                            {{ errors.cover_image[0] }}
                        </p>

                        <!-- Botón quitar imagen -->
                        <button v-if="coverPreview" type="button" @click.stop="removeCover" class="mt-3 w-full px-3 py-2 rounded-xl text-xs font-semibold transition-colors
                     bg-red-50 dark:bg-red-500/10
                     text-red-600 dark:text-red-400
                     hover:bg-red-100 dark:hover:bg-red-500/20">
                            Quitar imagen
                        </button>
                    </div>

                    <!-- ── Columna derecha: Formulario ───────────────────── -->
                    <div class="lg:col-span-2 space-y-5">

                        <!-- Título -->
                        <div>
                            <label class="field-label">
                                Título <span class="text-red-500">*</span>
                            </label>
                            <input v-model="form.title" type="text" placeholder="Ej: Shingeki no Kyojin"
                                :class="['field-input', errors.title && 'field-input-error']">
                            <FieldError :errors="errors.title" />
                        </div>

                        <!-- Descripción -->
                        <div>
                            <label class="field-label">Descripción</label>
                            <textarea v-model="form.description" rows="4" placeholder="Sinopsis del manga..."
                                :class="['field-input resize-none', errors.description && 'field-input-error']"></textarea>
                            <FieldError :errors="errors.description" />
                        </div>

                        <!-- Author y Artist en fila -->
                        <div class="grid grid-cols-2 gap-4">
                            <div>
                                <label class="field-label">Autor</label>
                                <input v-model="form.author" type="text" placeholder="Nombre del autor"
                                    :class="['field-input', errors.author && 'field-input-error']">
                                <FieldError :errors="errors.author" />
                            </div>
                            <div>
                                <label class="field-label">Artista</label>
                                <input v-model="form.artist" type="text" placeholder="Nombre del artista"
                                    :class="['field-input', errors.artist && 'field-input-error']">
                                <FieldError :errors="errors.artist" />
                            </div>
                        </div>

                        <!-- Status, Tipo y Año en fila -->
                        <div class="grid grid-cols-3 gap-4">
                            <div>
                                <label class="field-label">
                                    Estado <span class="text-red-500">*</span>
                                </label>
                                <select v-model="form.status"
                                    :class="['field-input', errors.status && 'field-input-error']">
                                    <option value="">Seleccionar</option>
                                    <option v-for="s in statusOptions" :key="s.value" :value="s.value">
                                        {{ s.label }}
                                    </option>
                                </select>
                                <FieldError :errors="errors.status" />
                            </div>
                            <div>
                                <label class="field-label">
                                    Tipo <span class="text-red-500">*</span>
                                </label>
                                <select v-model="form.type"
                                    :class="['field-input', errors.type && 'field-input-error']">
                                    <option value="">Seleccionar</option>
                                    <option v-for="t in typeOptions" :key="t.value" :value="t.value">
                                        {{ t.label }}
                                    </option>
                                </select>
                                <FieldError :errors="errors.type" />
                            </div>
                            <div>
                                <label class="field-label">Año</label>
                                <input v-model.number="form.release_year" type="number" :min="1900" :max="currentYear"
                                    placeholder="2024"
                                    :class="['field-input', errors.release_year && 'field-input-error']">
                                <FieldError :errors="errors.release_year" />
                            </div>
                        </div>

                        <!-- Géneros -->
                        <div>
                            <label class="field-label">Géneros</label>
                            <div v-if="loadingMeta" class="h-10 rounded-xl animate-pulse bg-gray-100 dark:bg-gray-800">
                            </div>
                            <div v-else class="flex flex-wrap gap-2">
                                <button v-for="genre in genres" :key="genre.id" type="button"
                                    @click="toggleGenre(genre.id)" :class="[
                                        'px-3 py-1.5 rounded-full text-xs font-semibold transition-all duration-150',
                                        form.genres.includes(genre.id)
                                            ? 'bg-red-500 text-white shadow-sm'
                                            : 'bg-gray-100 dark:bg-gray-800 text-gray-600 dark:text-gray-400 hover:bg-gray-200 dark:hover:bg-gray-700',
                                    ]">
                                    {{ genre.name }}
                                </button>
                            </div>
                            <FieldError :errors="errors.genres" />
                        </div>

                        <!-- Tags — agrupados por categoría -->
                        <div>
                            <label class="field-label">Tags</label>
                            <div v-if="loadingMeta" class="h-10 rounded-xl animate-pulse bg-gray-100 dark:bg-gray-800">
                            </div>
                            <div v-else class="space-y-3">
                                <div v-for="(items, category) in groupedTags" :key="category">
                                    <p class="text-[10px] font-bold uppercase tracking-widest text-gray-400 mb-1.5">
                                        {{ categoryLabels[category as string] ?? category }}
                                    </p>
                                    <div class="flex flex-wrap gap-1.5">
                                        <button v-for="tag in items" :key="tag.id" type="button"
                                            @click="toggleTag(tag.id)" :class="[
                                                'px-2.5 py-1 rounded-full text-xs font-medium transition-all duration-150',
                                                form.tags.includes(tag.id)
                                                    ? 'bg-red-500 text-white'
                                                    : 'bg-gray-100 dark:bg-gray-800 text-gray-600 dark:text-gray-400 hover:bg-gray-200 dark:hover:bg-gray-700',
                                            ]">
                                            {{ tag.name }}
                                        </button>
                                    </div>
                                </div>
                            </div>
                            <FieldError :errors="errors.tags" />
                        </div>

                        <!-- Error general del servidor -->
                        <div v-if="serverError"
                            class="p-4 rounded-xl bg-red-50 dark:bg-red-500/10 border border-red-200 dark:border-red-500/20">
                            <p class="text-sm text-red-600 dark:text-red-400 font-medium">{{ serverError }}</p>
                        </div>

                        <!-- Botones -->
                        <div class="flex gap-3 pt-2">
                            <RouterLink to="/dashboard" class="flex-1">
                                <button type="button" class="w-full px-6 py-3 rounded-xl font-bold text-sm transition-colors
                         border border-gray-200 dark:border-white/10
                         bg-white dark:bg-gray-900
                         text-gray-700 dark:text-gray-300
                         hover:bg-gray-50 dark:hover:bg-gray-800">
                                    Cancelar
                                </button>
                            </RouterLink>
                            <button type="submit" :disabled="submitting" class="flex-1 px-6 py-3 rounded-xl font-bold text-sm transition-all duration-200
                       bg-red-500 hover:bg-red-400 text-white
                       disabled:opacity-50 disabled:cursor-not-allowed
                       flex items-center justify-center gap-2">
                                <svg v-if="submitting" class="w-4 h-4 animate-spin" fill="none" viewBox="0 0 24 24">
                                    <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor"
                                        stroke-width="4" />
                                    <path class="opacity-75" fill="currentColor"
                                        d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4z" />
                                </svg>
                                {{ submitting ? 'Publicando...' : 'Publicar manga' }}
                            </button>
                        </div>

                    </div>
                </div>
            </form>

        </div>
    </div>
</template>

<script setup lang="ts">
import { ref, reactive, onMounted, defineComponent, h } from 'vue';
import { RouterLink, useRouter } from 'vue-router';
import { createManga } from '@/repositories/Manga';
import alertService from '@/helpers/sweetalert';
import type { Genre, Tag, ParamsManga } from '@/types/Manga';
import { getGenres, getTags } from '@/repositories/General';

const FieldError = defineComponent({
    props: { errors: { type: Array as () => string[] | undefined, default: undefined } },
    setup(props) {
        return () => props.errors?.length
            ? h('p', { class: 'mt-1.5 text-xs text-red-500' }, props.errors[0])
            : null
    },
})

const router = useRouter();

type ValidationErrors = Record<string, string[]>

const form = reactive({
    title: '',
    description: '',
    author: '',
    artist: '',
    release_year: null as number | null,
    status: '',
    type: '',
    genres: [] as number[],
    tags: [] as number[],
} as ParamsManga);

const coverFile = ref<File | null>(null)
const coverPreview = ref<string | null>(null)
const coverInput = ref<HTMLInputElement | null>(null)
const isDragging = ref(false)
const submitting = ref(false)
const errors = ref<ValidationErrors>({})
const serverError = ref<string | null>(null)

const genres = ref<Genre[]>([])
const groupedTags = ref<Record<string, Tag[]>>({})
const loadingMeta = ref(true)

const currentYear = new Date().getFullYear()

const statusOptions = [
    { value: 'ongoing', label: 'En curso' },
    { value: 'completed', label: 'Completado' },
    { value: 'hiatus', label: 'En pausa' },
    { value: 'cancelled', label: 'Cancelado' },
]

const typeOptions = [
    { value: 'manga', label: 'Manga' },
    { value: 'manhwa', label: 'Manhwa' },
    { value: 'manhua', label: 'Manhua' },
    { value: 'novel', label: 'Novel' },
    { value: 'one-shot', label: 'One-Shot' },
];

const categoryLabels: Record<string, string> = {
    setting: 'Ambientación',
    fantasy: 'Fantasía',
    creature: 'Criaturas',
    character: 'Personaje',
    romance: 'Romance',
    thematic: 'Temático',
};

const loadMeta = async (): Promise<void> => {
    try {
        const [genresRes, tagRes] = await Promise.all([
            getGenres(),
            getTags(),
        ]);

        genres.value = genresRes ?? []
        groupedTags.value = tagRes.grouped ?? {}
    } catch {
        // Si falla, el formulario sigue funcionando sin géneros/tags
    } finally {
        loadingMeta.value = false
    }
};

const handleCoverChange = (event: Event): void => {
    const input = event.target as HTMLInputElement;
    const file = input.files?.[0];
    if (file) setCover(file);
};

const handleDrop = (event: DragEvent): void => {
    isDragging.value = false;
    const file = event.dataTransfer?.files?.[0];
    if (file && file.type.startsWith('image/')) {
        setCover(file);
    }
};

const setCover = (file: File): void => {
    // Validación de tamaño en cliente (5MB)
    if (file.size > 5 * 1024 * 1024) {
        errors.value.cover_image = ['La imagen no puede superar 5MB.'];
        return;
    }
    coverFile.value = file;
    coverPreview.value = URL.createObjectURL(file);
    // Limpiar error si existía
    delete errors.value.cover_image;
};

const removeCover = (): void => {
    coverFile.value = null;
    coverPreview.value = null;
    if (coverInput.value) coverInput.value.value = '';
};

const toggleGenre = (id: number): void => {
    const idx = form.genres.indexOf(id);
    idx === -1 ? form.genres.push(id) : form.genres.splice(idx, 1);
};

const toggleTag = (id: number): void => {
    const idx = form.tags.indexOf(id);
    idx === -1 ? form.tags.push(id) : form.tags.splice(idx, 1);
};

const handleSubmit = async (): Promise<void> => {
    errors.value = {}
    serverError.value = null

    // Validación mínima en cliente para UX inmediata
    const clientErrors: ValidationErrors = {}
    if (!form.title.trim()) clientErrors.title = ['El título es requerido.']
    if (!form.status) clientErrors.status = ['El estado es requerido.']
    if (!form.type) clientErrors.type = ['El tipo es requerido.']
    if (!coverFile.value) clientErrors.cover_image = ['La portada es requerida.']

    if (Object.keys(clientErrors).length > 0) {
        errors.value = clientErrors;
        return;
    }

    submitting.value = true;

    try {
        const manga = await createManga(form);
        await alertService.toast.success(`"${manga.title}" publicado correctamente`)
        router.push(`/dashboard`);

    } catch (err: any) {
        // ✅ Errores de validación Laravel (422)
        if (err.response?.status === 422) {
            errors.value = err.response.data.errors ?? {}
            serverError.value = err.response.data.message ?? null
        } else if (err.response?.status === 422) {
            serverError.value = err.response.data.message
        } else if (err.response?.status === 403) {
            serverError.value = 'No tienes permisos para publicar mangas.';
        } else {
            serverError.value = 'Ocurrió un error inesperado. Intenta de nuevo.';
        }
    } finally {
        submitting.value = false;
    }
};

onMounted(() => {
  loadMeta();
});
</script>

<style scoped>
.field-label {
    @apply block text-xs font-semibold uppercase tracking-[0.15em] text-gray-400 mb-1.5;
}

.field-input {
    @apply w-full px-4 py-2.5 rounded-xl text-sm transition-colors bg-white dark:bg-gray-900 border border-gray-200 dark:border-white/10 text-gray-900 dark:text-white placeholder-gray-400 dark:placeholder-gray-600 focus:outline-none focus:border-red-400 dark:focus:border-red-500;
}

.field-input-error {
    @apply border-red-400 dark:border-red-500 bg-red-50/50 dark:bg-red-500/5;
}
</style>
