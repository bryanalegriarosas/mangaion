import type { Roles } from "./Roles";

type ISODateString = string;

export interface RegisterParams {
  name: string
  last_name?: string
  username?: string
  email: string
  password: string
  password_confirmation: string
  avatar?: File | null,
};

export interface LoginParams {
  email: string
  password: string
  remember?: boolean
};

export interface RegisterResponse {
  message: string,
  token: string,
  user: User,
};

export interface LoginResponse {
  message: string,
  token: string,
  user: User,
};

export interface User {
  id: number,
  name: string,
  last_name?: string,
  username?: string,
  email: string,
  email_verified_at?: string | null,
  avatar?: string | null,
  created_at?: ISODateString,
  updated_at?: ISODateString,
  roles?: Roles[];
  chaptersRead?: number,
  favorites?: number,
  following?: number,
};

