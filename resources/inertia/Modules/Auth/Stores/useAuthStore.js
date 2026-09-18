import { defineStore } from 'pinia'
import { ref } from 'vue'

const useAuthStore = defineStore('auth-store', () => {
    // true selama gate verifikasi sesi di DashboardLayout aktif;
    // LoaderOverlay global mengalah agar tidak double loader.
    const gateChecking = ref(false);
    function setGateChecking(value) {
        gateChecking.value = !!value;
    }

    function setIsAuthenticated(value) {
        localStorage.setItem('access_token', value)
    }

    function logout() {
        localStorage.removeItem('access_token')
    }
    return {
        gateChecking,
        setGateChecking,
        setIsAuthenticated,
        logout,
    }
})

export default useAuthStore
