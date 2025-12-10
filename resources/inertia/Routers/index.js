import { createRouter, createWebHistory } from 'vue-router'

// import halaman
import homePage from '@/inertia/Modules/Home/Pages/IndexPage.vue'
import masterProduct from '@/inertia/Modules/MastersProducts/Pages/IndexPage.vue'
import masterCategory from '@/inertia/Modules/MastersCategories/Pages/IndexPage.vue'
import masterRegion from '@/inertia/Modules/MastersRegions/Pages/IndexPage.vue'

const routes = [
    { path: '/', component: homePage },
    { path: '/master-product', component: masterProduct },
    { path: '/master-category', component: masterCategory },
    { path: '/master-region', component: masterRegion },
]

const router = createRouter({
    history: createWebHistory(),
    routes,
})

export default router
