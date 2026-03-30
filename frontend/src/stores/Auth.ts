import { defineStore } from 'pinia'
import { computed, ref } from 'vue'
import Api from '@/services/Api'
import type { AuthUser } from '@/types/Manga'

export const useAuthStore = defineStore('auth', () => {
  // ─── State ──────────────────────────────────────────────────────
  const user  = ref<AuthUser | null>(null)
  const token = ref<string | null>(localStorage.getItem('auth_token'))

  // ─── Getters ────────────────────────────────────────────────────
  const isAuth      = computed(() => !!user.value)
  const displayName = computed(() => user.value?.name ?? '')

  // ─── Actions ────────────────────────────────────────────────────

  function setUser(data: AuthUser): void {
    user.value = data
  }

  function setToken(newToken: string): void {
    token.value = newToken
    localStorage.setItem('auth_token', newToken)
  }

  /**
   * Llamado desde HomeController — el /home ya devuelve el user
   * si el token es válido. No hace falta una llamada extra al backend.
   */
  function hydrateFromHome(data: AuthUser): void {
    user.value = data
  }

  async function logout(): Promise<void> {
    try {
      await Api.post('/logout')
    } finally {
      // Limpiar estado aunque falle la llamada al backend
      user.value  = null
      token.value = null
      localStorage.removeItem('auth_token')
    }
  }

  return {
    // state
    user,
    token,
    // getters
    isAuth,
    displayName,
    // actions
    setUser,
    setToken,
    hydrateFromHome,
    logout,
  }
});
