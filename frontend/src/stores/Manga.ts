import { defineStore } from 'pinia'
import api from '../services/Api'
import type { Manga } from '../types/Manga';

export const useMangaStore = defineStore('manga', {
  state: () => ({
    mangas: [] as Manga[],
    currentManga: null as Manga | null,
    loading: false,
  }),

  actions: {
    async getMangas() {
      this.loading = true

      try {
        const res = await api.get('/mangas')
        this.mangas = res.data.data
      } finally {
        this.loading = false
      }
    },
    async getManga(slug: string) {
        this.loading = true

        try {
            const res = await api.get(`/mangas/${slug}`)
            this.currentManga = res.data
        } finally {
            this.loading = false
        }
    }
  }
});