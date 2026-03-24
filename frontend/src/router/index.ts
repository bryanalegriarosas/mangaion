import { createRouter, createWebHistory } from 'vue-router'

// Vistas
import Home from '../views/Home.vue'

const routes = [
  {
    path: '/',
    name: 'home',
    component: Home,
  },
  {
    path: '/mangas',
    name: 'manga.list',
    component: () => import('@/views/Manga/MangaList.vue'),
  },
  {
    path: '/manga/:slug',
    name: 'manga.show',
    component: () => import('@/views/Manga/MangaDetail.vue'),
    props: true,
  },
  {
    path: '/chapter/:id',
    name: 'chapter.show',
    component: () => import('@/views/Chapter/ChapterReader.vue'),
  },
  {
    path: '/manga/:slug/upload',
    name: 'chapter.create',
    component: () => import('@/views/Chapter/ChapterCreate.vue'),
  },
]

const router = createRouter({
  history: createWebHistory(),
  routes,
})

export default router