import { defineStore } from 'pinia';
import api from '@/services/api'; 

export const useAuthStore = defineStore('auth', {
    state: () => ({
        user: JSON.parse(localStorage.getItem('user')) || null,
        token: localStorage.getItem('token') || null,
        loading: false,
        error: null
    }),
    getters: {
        isAuthenticated: (state) => !!state.token,
        userRole: (state) => state.user ? state.user.role : null,
        userName: (state) => state.user ? state.user.name : '',
    },
    actions: {
        async login(email, password) {
            this.loading = true;
            this.error = null;
            try {
                const response = await api.post('/login', { email, password });
                
                this.token = response.data.data.token; 
                this.user = response.data.data.user;   
                
                localStorage.setItem('token', this.token);
                localStorage.setItem('user', JSON.stringify(this.user));
                
                return true; 
            } catch (err) {
                this.error = err.response?.data?.message || 'Login gagal. Periksa email dan password.';
                return false; 
            } finally {
                this.loading = false;
            }
        },

        async register(userData) {
            this.loading = true;
            this.error = null;
            try {
                const response = await api.post('/register', userData);
                
                this.token = response.data.data.token; 
                this.user = response.data.data.user;   
                
                localStorage.setItem('token', this.token);
                localStorage.setItem('user', JSON.stringify(this.user));
                
                return true; 
            } catch (err) {
                if (err.response?.status === 422 && err.response?.data?.errors) {
                    const firstErrorKey = Object.keys(err.response.data.errors)[0];
                    this.error = err.response.data.errors[firstErrorKey][0];
                } else {
                    this.error = err.response?.data?.message || 'Registrasi gagal. Silakan coba lagi.';
                }
                return false; 
            } finally {
                this.loading = false;
            }
        },

        logout() {
            this.user = null;
            this.token = null;
            localStorage.removeItem('token');
            localStorage.removeItem('user');
        }
    }
});
