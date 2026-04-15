import { createRouter, createWebHistory } from 'vue-router'

const routes = [
  {
    path: '/',
    name: 'home',
    component: () => import('@/views/Home.vue'),
  },
  {
    path: '/register',
    name: 'register',
    component: () => import('@/views/Auth/Register.vue'),
  },
  {
    path: '/login',
    name: 'login',
    component: () => import('@/views/Auth/Login.vue'),
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
  {
    path: '/dashboard',
    name: 'dashboard',
    component: () => import('@/views/Dashboard.vue'),
    meta: { requiresAuth: true }
  },
]

const router = createRouter({
  history: createWebHistory(),
  routes,
})

export default router