import axios from 'axios';
import useAuthStore from "@/inertia/Modules/Auth/Stores/useAuthStore";
import { router } from '@inertiajs/vue3';

const defaultConfig = {
    baseURL: import.meta.env.VITE_API_URL || 'http://localhost:8000/api',
};

const axiosInstance = axios.create(defaultConfig);

axiosInstance.interceptors.request.use(
    config => {
        const token = localStorage.getItem('access_token');
        if (token) {
            config.headers.Authorization = `Bearer ${token}`;
        }
        return config;
    },
    error => Promise.reject(error)
);

axiosInstance.interceptors.response.use(
    async response => response,
    // bisa digunakan untuk melakukan parsing response, tetapi harus hati-hati dalam implementasinya
    // karena ini bersifat global
    error => {
        const authStore = useAuthStore()
        const status = error.response?.status
        if (status === 401) {
            authStore.logout()
            router.visit ('/login');
        }

        if (axios.isCancel(error)) {
            return Promise.reject(new Error(error));
        }
        try {
            const errMessage = error.response?.data?.message;
            return Promise.reject(new Error(errMessage));
        } catch (e) {
            console.info('Failed to parsing response message: ', e);
        }

        return Promise.reject(new Error(error));
    }
);

const useAxios = () => axiosInstance;

export default useAxios;
