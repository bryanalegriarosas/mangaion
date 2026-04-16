import Api from '@/services/Api'
import type { Genre, TagResponse } from '@/types/Manga';

export const getGenres = async (): Promise<Genre[]> => {
  const { data } = await Api.get<Genre[]>('genres');
  return data;
};

export const getTags = async (): Promise<TagResponse> => {
  const { data } = await Api.get<TagResponse>('tags');
  return data;
};
