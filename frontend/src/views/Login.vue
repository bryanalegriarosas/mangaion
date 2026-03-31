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
            <span class="bg-gradient-to-r from-red-400 via-pink-400 to-purple-400 bg-clip-text text-transparent">ion</span>
          </h1>
          <p class="text-gray-600 dark:text-gray-400">Bienvenido de vuelta</p>
        </div>

        <!-- Login Form -->
        <div class="relative rounded-2xl p-8 backdrop-blur-sm
                    bg-white/90 dark:bg-gray-900/90
                    border border-gray-200 dark:border-white/10
                    shadow-[0_0_40px_rgba(0,0,0,0.1)] dark:shadow-[0_0_40px_rgba(0,0,0,0.3)]">

          <!-- Loading overlay -->
          <div v-if="loading" class="absolute inset-0 rounded-2xl bg-white/80 dark:bg-gray-900/80 backdrop-blur-sm flex items-center justify-center z-10">
            <div class="text-center">
              <div class="w-8 h-8 border-2 border-red-500 border-t-transparent rounded-full animate-spin mx-auto mb-2"></div>
              <p class="text-sm text-gray-600 dark:text-gray-400">Iniciando sesión...</p>
            </div>
          </div>

          <!-- Error message -->
          <div v-if="error" class="mb-6 p-4 rounded-lg
                      bg-red-50 dark:bg-red-500/10
                      border border-red-200 dark:border-red-500/30
                      text-red-700 dark:text-red-400 text-sm">
            {{ error }}
          </div>

          <form @submit.prevent="handleLogin" class="space-y-5">
            
            <!-- Email -->
            <div>
              <label for="email" class="block text-sm font-medium mb-2
                                     text-gray-700 dark:text-gray-300">
                Email *
              </label>
              <input
                id="email"
                v-model="form.email"
                type="email"
                required
                class="w-full px-4 py-3 rounded-lg
                       bg-white dark:bg-gray-800
                       border border-gray-300 dark:border-gray-600
                       focus:border-red-500 dark:focus:border-red-400
                       focus:ring-2 focus:ring-red-500/20 dark:focus:ring-red-400/20
                       transition-colors
                       text-gray-900 dark:text-white
                       placeholder:gray-500 dark:placeholder-gray-400"
                placeholder="email@example.com"
              />
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
              <div class="relative">
                <input
                  id="password"
                  v-model="form.password"
                  :type="showPassword ? 'text' : 'password'"
                  required
                  class="w-full px-4 py-3 pr-12 rounded-lg
                         bg-white dark:bg-gray-800
                         border border-gray-300 dark:border-gray-600
                         focus:border-red-500 dark:focus:border-red-400
                         focus:ring-2 focus:ring-red-500/20 dark:focus:ring-red-400/20
                         transition-colors
                         text-gray-900 dark:text-white
                         placeholder:gray-500 dark:placeholder-gray-400"
                  placeholder="••••••••"
                />
                <button
                  type="button"
                  @click="showPassword = !showPassword"
                  class="absolute right-3 top-1/2 transform -translate-y-1/2
                         text-gray-400 hover:text-gray-600 dark:hover:text-gray-300
                         transition-colors">
                  <svg v-if="showPassword" class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" 
                          d="M13.875 18.825A10.05 10.05 0 0112 19c-4.478 0-8.268-2.943-9.543-7a9.97 9.97 0 011.563-3.029m5.858.908a3 3 0 114.243 4.243M9.878 9.878l4.242 4.242M9.88 9.88l-3.29-3.29m7.532 7.532l3.29 3.29M3 3l3.59 3.59m0 0A9.953 9.953 0 0112 5c4.478 0 8.268 2.943 9.543 7a10.025 10.025 0 01-4.132 5.411m0 0L21 21" />
                  </svg>
                  <svg v-else class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" 
                          d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" />
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" 
                          d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z" />
                  </svg>
                </button>
              </div>
              <p v-if="errors.password" class="mt-1 text-xs text-red-500 dark:text-red-400">
                {{ errors.password }}
              </p>
            </div>

            <!-- Forgot password -->
            <div class="flex items-center justify-between">
              <a href="#" class="text-sm text-red-500 dark:text-red-400 hover:underline">
                ¿Olvidaste tu contraseña?
              </a>
            </div>

            <!-- Submit button -->
            <button
              type="submit"
              :disabled="loading"
              class="w-full py-4 rounded-xl font-bold text-lg text-white
                     bg-red-500 hover:bg-red-400
                     disabled:bg-gray-300 disabled:cursor-not-allowed
                     shadow-[0_0_30px_rgba(239,68,68,0.3)] hover:shadow-[0_0_50px_rgba(239,68,68,0.5)]
                     transform hover:scale-105 transition-all duration-200
                     disabled:transform-none disabled:shadow-none">
              Iniciar sesión
            </button>
          </form>

          <!-- Register link -->
          <div class="mt-6 text-center">
            <p class="text-sm text-gray-600 dark:text-gray-400">
              ¿No tienes cuenta?
              <RouterLink to="/register" class="font-medium text-red-500 dark:text-red-400 hover:underline">
                Regístrate gratis
              </RouterLink>
            </p>
          </div>
        </div>
      </div>
    </div>
  </div>
</template>

<script setup lang="ts">
import { ref, reactive } from 'vue'
import { useRouter } from 'vue-router'
import { useAuthStore } from '@/stores/Auth'
import { login } from '@/repositories/Auth'
import type { LoginParams } from '@/types/Auth'

// ─── Router & Store ───────────────────────────────────────────────────────
const router = useRouter()
const store = useAuthStore()

// ─── State ──────────────────────────────────────────────────────────────────
const loading = ref(false)
const error = ref<string | null>(null)
const showPassword = ref(false)

const form = reactive<LoginParams>({
  email: '',
  password: '',
  remember: false
})

const errors = reactive({
  email: '',
  password: ''
})

// ─── Methods ───────────────────────────────────────────────────────────────
const validateForm = (): boolean => {
  // Reset errors
  Object.keys(errors).forEach(key => {
    errors[key as keyof typeof errors] = ''
  })

  let isValid = true

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
  }

  return isValid
}

const handleLogin = async (): Promise<void> => {
  if (!validateForm()) return

  loading.value = true
  error.value = null

  try {
    const response = await login(form)
    
    // Guardar token y usuario
    store.setToken(response.token)
    store.setUser(response.user)
    
    // Redirect to home after successful login
    router.push({ name: 'home' })
  } catch (err: any) {
    console.error('[Login] Error:', err)
    
    // Handle different types of errors
    if (err.response?.data?.errors) {
      // Laravel validation errors
      const serverErrors = err.response.data.errors
      Object.keys(serverErrors).forEach(key => {
        if (key in errors) {
          errors[key as keyof typeof errors] = serverErrors[key][0]
        }
      })
    } else if (err.response?.data?.message) {
      // General error message
      error.value = err.response.data.message
    } else if (err.response?.status === 401) {
      // Invalid credentials
      error.value = 'Email o contraseña incorrectos'
    } else if (err.message) {
      // Network or other error
      error.value = 'No se pudo iniciar sesión. Intenta de nuevo.'
    } else {
      error.value = 'Ocurrió un error inesperado'
    }
  } finally {
    loading.value = false
  }
}
</script>
