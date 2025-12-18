import { defineStore } from 'pinia'
import { ref } from 'vue'

const useAuthStore = defineStore('auth-store', () => {
    function setIsAuthenticated(value) {
        localStorage.setItem('access_token', value)
    }

    function logout() {
        localStorage.removeItem('access_token')
    }
    return {
        setIsAuthenticated,
        logout,
    }
})

export default useAuthStore
