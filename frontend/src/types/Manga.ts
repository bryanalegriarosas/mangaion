import type { User } from "./Auth"

// ── GenreResource ────────────────────────────────────────────────
export interface Genre {
  id:   number
  name: string
  slug: string
}

export interface MangaFilters {
  search?:  string
  genre?:   string
  tag?:     string
  status?:  string
  type?:    string
  sort?:    'latest' | 'popular' | 'favorites'
  page?:    number
}

// ── MangaResource ────────────────────────────────────────────────
export interface Manga {
  id:     number
  title:  string
  slug:   string
  cover:  string | null
  status: 'ongoing' | 'completed' | 'hiatus' | 'cancelled'
  type:   'manga' | 'manhwa' | 'manhua' | 'novel' | 'one-shot'
  genre:  string | null   // primer género (shortcut)
  genres: Genre[]
  rating: number | null
}

// ── PopularMangaResource (extiende MangaResource) ────────────────
export interface PopularManga extends Manga {
  readers: number
  growth:  number | null  // % vs semana anterior, null si no hay datos previos
}

// ── ChapterVersionResource ───────────────────────────────────────
export interface ChapterVersion {
  id:             number
  slug:           string
  title:          string | null
  published_at:   string | null  // ISO 8601
  chapter_number: number
  volume_number:  number | null
  language: {
    code: string
    name: string
  }
  scan_group: {
    id:   number
    name: string
  }
  manga: {
    id:    number
    title: string
    slug:  string
    cover: string | null
  }
}

// ── ContinueReadingResource ──────────────────────────────────────
export interface ContinueReading {
  manga: {
    id:    number
    title: string
    slug:  string
    cover: string | null
  }
  chapter: {
    id:             number
    slug:           string
    title:          string | null
    chapter_number: number
  }
  page:       number
  totalPages: number
  progress:   number  // 0-100
}

// ── Respuesta completa del endpoint GET /home ────────────────────
export interface HomeResponse {
  trending:       Manga[]
  popular:        PopularManga[]
  latestChapters: ChapterVersion[]
  // Solo presentes si el usuario está autenticado
  user?:            User,
  continueReading?: ContinueReading[]
  recommended?:     Manga[]
}

// ── Laravel paginator (para índices como /mangas) ────────────────
export interface PaginatedResponse<T> {
  data: T[]
  links: {
    first: string
    last:  string
    prev:  string | null
    next:  string | null
  }
  meta: {
    current_page: number
    from:         number
    last_page:    number
    per_page:     number
    to:           number
    total:        number
  }
}
