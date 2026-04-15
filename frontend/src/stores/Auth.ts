import type { User } from "@/types/Auth";
import { defineStore } from "pinia";

export const useAuthStore = defineStore('auth', {
    state: () => ({
        user: null as User | null,
        token: null as string | null,
    }),
    getters: {
        isAuth: (state) => {
            return !!state.user;
        },
        displayName: (state) => {
            return state.user?.name ?? '';
        },
    },
    actions: {
        setUser(user: User): void {
            this.user = user;
        },
        setToken(token: string): void {
            this.token = token;
            localStorage.setItem('auth_token', token);
        },
        hydrateFromHome(data: User): void {
            this.user = data;
        },
        clearToken(): void {
            this.user = null;
            this.token = null;
        },
    },
});
