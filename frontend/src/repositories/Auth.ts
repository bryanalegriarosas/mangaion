import Api from '@/services/Api'
import type { RegisterParams, RegisterResponse } from '@/types/Auth';

export const register = async (params: RegisterParams): Promise<RegisterResponse> => {
  const { data } = await Api.postForm<RegisterResponse>('register', params)
  return data;
};

export const logout = async (): Promise<void> => {
  const { data } = await Api.post('logout');
  return data;
};
