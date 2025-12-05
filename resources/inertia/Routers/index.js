import { createRouter, createWebHistory } from 'vue-router'

// import halaman
import homePage from '@/inertia/Modules/Home/Pages/IndexPage.vue'
import masterProduct from '@/inertia/Modules/MastersProducts/Pages/IndexPage.vue'

const routes = [
    { path: '/', component: homePage },
    { path: '/master-product', component: masterProduct },
]

const router = createRouter({
    history: createWebHistory(),
    routes,
})

export default router
