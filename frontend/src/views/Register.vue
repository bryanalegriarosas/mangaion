<template>
  <div class="min-h-screen transition-colors duration-300
              bg-white dark:bg-[#0d0d12]
              text-gray-900 dark:text-gray-100">

    <!-- Background decoration -->
    <div class="absolute inset-0 overflow-hidden">
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
    </div>

    <div class="relative flex items-center justify-center min-h-screen px-6 py-12">
      <div class="w-full max-w-md">

        <!-- Logo/Brand -->
        <div class="text-center mb-8">
          <h1 class="text-4xl font-black mb-2">
            <span class="text-gray-900 dark:text-white">Manga</span>
            <span
              class="bg-gradient-to-r from-red-400 via-pink-400 to-purple-400 bg-clip-text text-transparent">ion</span>
          </h1>
          <p class="text-gray-600 dark:text-gray-400">Únete a esta comunidad</p>
        </div>

        <!-- Register Form -->
        <div class="relative rounded-2xl p-8 backdrop-blur-sm
                    bg-white/90 dark:bg-gray-900/90
                    border border-gray-200 dark:border-white/10
                    shadow-[0_0_40px_rgba(0,0,0,0.1)] dark:shadow-[0_0_40px_rgba(0,0,0,0.3)]">

          <!-- Loading overlay -->
          <div v-if="loading"
            class="absolute inset-0 rounded-2xl bg-white/80 dark:bg-gray-900/80 backdrop-blur-sm flex items-center justify-center z-10">
            <div class="text-center">
              <div class="w-8 h-8 border-2 border-red-500 border-t-transparent rounded-full animate-spin mx-auto mb-2">
              </div>
              <p class="text-sm text-gray-600 dark:text-gray-400">Creando cuenta...</p>
            </div>
          </div>

          <!-- Error message -->
          <div v-if="error" class="mb-6 p-4 rounded-lg
                      bg-red-50 dark:bg-red-500/10
                      border border-red-200 dark:border-red-500/30
                      text-red-700 dark:text-red-400 text-sm">
            {{ error }}
          </div>

          <form @submit.prevent="handleRegister" class="space-y-5">

            <!-- Name fields row -->
            <div class="grid grid-cols-2 gap-4">
              <div>
                <label for="name" class="block text-sm font-medium mb-2
                                       text-gray-700 dark:text-gray-300">
                  Nombre *
                </label>
                <input id="name" v-model="form.name" type="text" required class="w-full px-4 py-3 rounded-lg
                         bg-white dark:bg-gray-800
                         border border-gray-300 dark:border-gray-600
                         focus:border-red-500 dark:focus:border-red-400
                         focus:ring-2 focus:ring-red-500/20 dark:focus:ring-red-400/20
                         transition-colors
                         text-gray-900 dark:text-white
                         placeholder:gray-500 dark:placeholder-gray-400" placeholder="Juan" />
                <p v-if="errors.name" class="mt-1 text-xs text-red-500 dark:text-red-400">
                  {{ errors.name }}
                </p>
              </div>

              <div>
                <label for="last_name" class="block text-sm font-medium mb-2
                                       text-gray-700 dark:text-gray-300">
                  Apellido
                </label>
                <input id="last_name" v-model="form.last_name" type="text" class="w-full px-4 py-3 rounded-lg
                         bg-white dark:bg-gray-800
                         border border-gray-300 dark:border-gray-600
                         focus:border-red-500 dark:focus:border-red-400
                         focus:ring-2 focus:ring-red-500/20 dark:focus:ring-red-400/20
                         transition-colors
                         text-gray-900 dark:text-white
                         placeholder:gray-500 dark:placeholder-gray-400" placeholder="Pérez" />
                <p v-if="errors.last_name" class="mt-1 text-xs text-red-500 dark:text-red-400">
                  {{ errors.last_name }}
                </p>
              </div>
            </div>

            <!-- Avatar Upload -->
            <div>
              <label class="block text-sm font-medium mb-2
                             text-gray-700 dark:text-gray-300">
                Foto de perfil
              </label>
              <div class="flex items-center gap-4">
                <!-- Avatar Preview -->
                <div class="relative">
                  <div class="w-20 h-20 rounded-full overflow-hidden
                              bg-gray-200 dark:bg-gray-700
                              border-2 border-gray-300 dark:border-gray-600
                              flex items-center justify-center">
                    <img v-if="avatarPreview" 
                         :src="avatarPreview" 
                         alt="Avatar preview"
                         class="w-full h-full object-cover">
                    <svg v-else 
                         class="w-8 h-8 text-gray-400 dark:text-gray-500" 
                         fill="none" 
                         stroke="currentColor" 
                         viewBox="0 0 24 24">
                      <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" 
                            d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z" />
                    </svg>
                  </div>
                  <!-- Remove button -->
                  <button v-if="form.avatar || avatarPreview"
                          type="button"
                          @click="removeAvatar"
                          class="absolute -top-1 -right-1 w-6 h-6 rounded-full
                                 bg-red-500 hover:bg-red-400 text-white
                                 flex items-center justify-center text-xs
                                 transition-colors">
                    ×
                  </button>
                </div>

                <!-- Upload button -->
                <div class="flex-1">
                  <input
                    id="avatar"
                    ref="avatarInput"
                    type="file"
                    accept="image/*"
                    @change="handleAvatarChange"
                    class="hidden"
                  />
                  <button
                    type="button"
                    @click="() => (avatarInput as HTMLInputElement).click()"
                    class="px-4 py-2 rounded-lg
                           bg-gray-100 hover:bg-gray-200 dark:bg-gray-800 dark:hover:bg-gray-700
                           border border-gray-300 dark:border-gray-600
                           text-sm font-medium
                           text-gray-700 dark:text-gray-300
                           transition-colors">
                    {{ form.avatar ? 'Cambiar imagen' : 'Subir imagen' }}
                  </button>
                  <p class="mt-1 text-xs text-gray-500 dark:text-gray-400">
                    JPG, JPEG, PNG o WebP (máx. 2MB)
                  </p>
                  <p v-if="errors.avatar" class="mt-1 text-xs text-red-500 dark:text-red-400">
                    {{ errors.avatar }}
                  </p>
                </div>
              </div>
            </div>

            <!-- Username -->
            <div>
              <label for="username" class="block text-sm font-medium mb-2
                                     text-gray-700 dark:text-gray-300">
                Nombre de usuario
              </label>
              <input id="username" v-model="form.username" type="text" class="w-full px-4 py-3 rounded-lg
                       bg-white dark:bg-gray-800
                       border border-gray-300 dark:border-gray-600
                       focus:border-red-500 dark:focus:border-red-400
                       focus:ring-2 focus:ring-red-500/20 dark:focus:ring-red-400/20
                       transition-colors
                       text-gray-900 dark:text-white
                       placeholder:gray-500 dark:placeholder-gray-400" placeholder="juanperez" />
              <p v-if="errors.username" class="mt-1 text-xs text-red-500 dark:text-red-400">
                {{ errors.username }}
              </p>
            </div>

            <!-- Email -->
            <div>
              <label for="email" class="block text-sm font-medium mb-2
                                     text-gray-700 dark:text-gray-300">
                Email *
              </label>
              <input id="email" v-model="form.email" type="email" required class="w-full px-4 py-3 rounded-lg
                       bg-white dark:bg-gray-800
                       border border-gray-300 dark:border-gray-600
                       focus:border-red-500 dark:focus:border-red-400
                       focus:ring-2 focus:ring-red-500/20 dark:focus:ring-red-400/20
                       transition-colors
                       text-gray-900 dark:text-white
                       placeholder:gray-500 dark:placeholder-gray-400" placeholder="juan@ejemplo.com" />
              <p v-if="errors.email" class="mt-1 text-xs text-red-500 dark:text-red-400">
                {{ errors.email }}
              </p>
            </div>

            <!-- Password -->
            <div>
              <label for="password" class="block text-sm font-medium mb-2
                                     text-gray-700 dark:text-gray-300">
                Contraseña *
              </label>
              <input id="password" v-model="form.password" type="password" required minlength="8" class="w-full px-4 py-3 rounded-lg
                       bg-white dark:bg-gray-800
                       border border-gray-300 dark:border-gray-600
                       focus:border-red-500 dark:focus:border-red-400
                       focus:ring-2 focus:ring-red-500/20 dark:focus:ring-red-400/20
                       transition-colors
                       text-gray-900 dark:text-white
                       placeholder:gray-500 dark:placeholder-gray-400" placeholder="Mínimo 8 caracteres" />
              <p v-if="errors.password" class="mt-1 text-xs text-red-500 dark:text-red-400">
                {{ errors.password }}
              </p>
            </div>

            <!-- Password Confirmation -->
            <div>
              <label for="password_confirmation" class="block text-sm font-medium mb-2
                                                   text-gray-700 dark:text-gray-300">
                Confirmar contraseña *
              </label>
              <input id="password_confirmation" v-model="form.password_confirmation" type="password" required
                minlength="8" class="w-full px-4 py-3 rounded-lg
                       bg-white dark:bg-gray-800
                       border border-gray-300 dark:border-gray-600
                       focus:border-red-500 dark:focus:border-red-400
                       focus:ring-2 focus:ring-red-500/20 dark:focus:ring-red-400/20
                       transition-colors
                       text-gray-900 dark:text-white
                       placeholder:gray-500 dark:placeholder-gray-400" placeholder="Repite tu contraseña" />
              <p v-if="errors.password_confirmation" class="mt-1 text-xs text-red-500 dark:text-red-400">
                {{ errors.password_confirmation }}
              </p>
            </div>

            <!-- Submit button -->
            <button type="submit" class="w-full py-4 rounded-xl font-bold text-lg text-white
                     bg-red-500 hover:bg-red-400
                     disabled:bg-gray-300 disabled:cursor-not-allowed
                     shadow-[0_0_30px_rgba(239,68,68,0.3)] hover:shadow-[0_0_50px_rgba(239,68,68,0.5)]
                     transform hover:scale-105 transition-all duration-200
                     disabled:transform-none disabled:shadow-none">
              Crear cuenta gratis
            </button>
          </form>

          <!-- Login link -->
          <div class="mt-6 text-center">
            <p class="text-sm text-gray-600 dark:text-gray-400">
              ¿Ya tienes cuenta?
              <RouterLink to="/login" class="font-medium text-red-500 dark:text-red-400 hover:underline">
                Inicia sesión
              </RouterLink>
            </p>
          </div>
        </div>
      </div>
    </div>
  </div>
</template>

<script setup lang="ts">
import { ref, reactive } from 'vue';
import { useRouter } from 'vue-router';
import { register } from '@/repositories/Auth';
import { useAuthStore } from '@/stores/Auth';
import type { RegisterParams } from '@/types/Auth';

const router = useRouter()
const store = useAuthStore();

const loading = ref(false);
const error = ref<string | null>(null);
const avatarInput = ref<HTMLInputElement | null>(null);
const avatarPreview = ref<string | null>(null);

const form = reactive<RegisterParams>({
  name: '',
  last_name: '',
  username: '',
  email: '',
  password: '',
  password_confirmation: '',
  avatar: null,
})

const errors = reactive<Record<keyof RegisterParams, string>>({
  name: '',
  last_name: '',
  username: '',
  email: '',
  password: '',
  password_confirmation: '',
  avatar: ''
})

const validateForm = (): boolean => {
  // Reset errors
  Object.keys(errors).forEach(key => {
    errors[key as keyof typeof errors] = ''
  })

  let isValid = true

  // Name validation
  if (!form.name.trim()) {
    errors.name = 'El nombre es obligatorio'
    isValid = false
  } else if (form.name.length < 2) {
    errors.name = 'El nombre debe tener al menos 2 caracteres'
    isValid = false
  }

  // Email validation
  const emailRegex = /^[^\s@]+@[^\s@]+\.[^\s@]+$/
  if (!form.email.trim()) {
    errors.email = 'El email es obligatorio'
    isValid = false
  } else if (!emailRegex.test(form.email)) {
    errors.email = 'El email no es válido'
    isValid = false
  }

  // Password validation
  if (!form.password) {
    errors.password = 'La contraseña es obligatoria'
    isValid = false
  } else if (form.password.length < 8) {
    errors.password = 'La contraseña debe tener al menos 8 caracteres'
    isValid = false
  }

  // Password confirmation validation
  if (!form.password_confirmation) {
    errors.password_confirmation = 'Debes confirmar tu contraseña'
    isValid = false
  } else if (form.password !== form.password_confirmation) {
    errors.password_confirmation = 'Las contraseñas no coinciden'
    isValid = false
  }

  // Avatar validation
  if (form.avatar && !form.avatar.type.startsWith('image/')) {
    errors.avatar = 'El archivo debe ser una imagen'
    isValid = false
  } else if (form.avatar && form.avatar.size > 2 * 1024 * 1024) {
    errors.avatar = 'La imagen no debe pesar más de 2MB'
    isValid = false
  }

  return isValid
}

const handleRegister = async (): Promise<void> => {
  if (!validateForm()) return

  loading.value = true
  error.value = null;

  try {
    const response = await register({
      ...form,
      avatar: form.avatar ? form.avatar : null,
    });
    console.log(response);
    store.setToken(response.token);
    store.setUser(response.user);
    router.push({ name: 'home' });
  } catch (error) {
    console.error('[Register] Error:', error)
  } finally {
    loading.value = false;
  }
}

// ─── Avatar Methods ───────────────────────────────────────────────────────
const handleAvatarChange = (event: Event): void => {
  const target = event.target as HTMLInputElement
  const file = target.files?.[0]
  
  if (file) {
    // Validar que sea una imagen
    if (!file.type.startsWith('image/')) {
      errors.avatar = 'El archivo debe ser una imagen'
      return
    }
    
    // Validar tamaño (2MB)
    if (file.size > 2 * 1024 * 1024) {
      errors.avatar = 'La imagen no debe pesar más de 2MB'
      return
    }
    
    // Limpiar error anterior
    errors.avatar = ''
    
    // Guardar archivo
    form.avatar = file
    
    // Crear preview
    const reader = new FileReader()
    reader.onload = (e) => {
      avatarPreview.value = e.target?.result as string
    }
    reader.readAsDataURL(file)
  }
}

const removeAvatar = (): void => {
  form.avatar = null
  avatarPreview.value = null
  errors.avatar = ''
  avatarInput.value = null
}
</script>
