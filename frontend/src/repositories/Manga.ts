import Api from '@/services/Api'
import type { Manga, PaginatedResponse, MangaFilters } from '@/types/Manga'

/**
 * Listado de mangas con filtros y paginación
 * GET /mangas
 */
export async function getMangas(filters?: MangaFilters): Promise<PaginatedResponse<Manga>> {
  const { data } = await Api.get('/mangas', { params: filters });
  return data;
};

/**
 * Detalle de un manga por ID
 * GET /mangas/:id
 */
export async function getManga(id: number): Promise<Manga> {
  const { data } = await Api.get(`/mangas/${id}`);
  return data.data;
};

// ─── Queries privadas (requieren auth) ───────────────────────────

/**
 * Mangas del usuario autenticado
 * GET /mangas/mine
 */
export async function getMyMangas(): Promise<Manga[]> {
  const { data } = await Api.get('/mangas/mine');
  return data.data ?? [];
};

/**
 * Crear un nuevo manga (multipart/form-data por el cover)
 * POST /mangas
 */
export async function createManga(payload: FormData): Promise<Manga> {
  const { data } = await Api.post('/mangas', payload, {
    headers: { 'Content-Type': 'multipart/form-data' },
  });
  return data.data;
};

/**
 * Actualizar un manga existente
 * PUT /mangas/:id  (usa _method: PUT dentro de FormData para multipart)
 */
export async function updateManga(id: number, payload: FormData): Promise<Manga> {
  // Laravel no acepta PUT con multipart — usamos POST con _method spoofing
  payload.append('_method', 'PUT');
  const { data } = await Api.post(`/mangas/${id}`, payload, {
    headers: { 'Content-Type': 'multipart/form-data' },
  });
  return data.data;
};

/**
 * Eliminar un manga
 * DELETE /mangas/:id
 */
export async function deleteManga(id: number): Promise<void> {
  await Api.delete(`/mangas/${id}`);
};
