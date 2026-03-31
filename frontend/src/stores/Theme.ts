import { defineStore } from 'pinia';
import { ref, computed, watch } from 'vue';

type Theme = 'dark' | 'light';

export const useThemeStore = defineStore('theme', () => {
  // 1. Prioridad: localStorage → 2. preferencia del sistema → 3. dark por defecto
  const stored     = localStorage.getItem('theme') as Theme | null;
  const systemDark = window.matchMedia('(prefers-color-scheme: dark)').matches;

  const theme = ref<Theme>(stored ?? (systemDark ? 'dark' : 'light'));

  const isDark  = computed(() => theme.value === 'dark');
  const isLight = computed(() => theme.value === 'light');

  function setTheme(value: Theme): void {
    theme.value = value;
  }

  function toggle(): void {
    theme.value = theme.value === 'dark' ? 'light' : 'dark';
  }

  // Sincroniza con clase del <html> y persiste en localStorage
  watch(theme, (value) => {
    const html = document.documentElement;
    value === 'dark'
      ? html.classList.add('dark')
      : html.classList.remove('dark');
    localStorage.setItem('theme', value);
  }, { immediate: true });

  return { theme, isDark, isLight, setTheme, toggle };
});
