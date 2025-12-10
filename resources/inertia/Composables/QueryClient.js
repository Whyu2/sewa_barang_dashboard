// src/Composables/QueryClient.js
import { QueryClient, QueryCache, MutationCache } from '@tanstack/vue-query'

let appInstance = null

export function registerApp(app) {
    appInstance = app
}

function showToast(message, severity = 'error') {
    if (!appInstance) return

    appInstance.config.globalProperties.$toast.add({
        severity,
        summary: 'Error',
        detail: message,
        life: 3000
    })
}

export const queryClient = new QueryClient({
    defaultOptions: {
        queries: {
            retry: false,
            staleTime: 5 * 60 * 1000,
        },
    },
    queryCache: new QueryCache({
        onError: (error) => {
            const message =
                error?.response?.data?.message ||
                error?.message ||
                'Terjadi kesalahan.'
            showToast(message)
        }
    }),

    mutationCache: new MutationCache({
        onError: (error) => {
            const message =
                error?.response?.data?.message ||
                error?.message ||
                'Gagal menyimpan data.'

            showToast(message)
        }
    })
})
