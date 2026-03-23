import { createRouter, createWebHistory } from 'vue-router'

// Vistas
import MangaList from '../views/MangaList.vue'

const routes = [
  {
    path: '/',
    name: 'home',
    component: MangaList,
  },
  {
    path: '/manga/:slug',
    name: 'manga.show',
    component: () => import('../views/MangaDetail.vue'),
    props: true,
  },
]

const router = createRouter({
  history: createWebHistory(),
  routes,
})

export default router