import Api from '@/services/Api'
import type { HomeResponse } from '@/types/Manga'

export async function getHomePage(): Promise<HomeResponse> {
  const { data } = await Api.get<HomeResponse>('home')
  return data
};
