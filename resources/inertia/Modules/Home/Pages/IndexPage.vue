<script setup>

import DashboardLayout from "@/inertia/Components/DashboardLayout.vue";
import StatsCard from "@/inertia/Modules/Home/Components/StatsCard.vue";
import homeUseQuery from "@/inertia/Modules/Home/Composables/homeUseQuery.js";
import {computed} from "vue";


const {useFetchProducts} = homeUseQuery();
const { data:  products, } = useFetchProducts();

const totalProducts = computed(() => {
    return products.value.length;
})
</script>

<template>
    <DashboardLayout>
        <div class="grid grid-cols-4 gap-4 mb-6">
            <StatsCard title="Total Barang" :value="totalProducts" icon="pi pi-box" />
            <StatsCard title="Sedang Dipinjam" :value="24" icon="pi pi-shopping-cart" />
            <StatsCard title="Tersedia" :value="24" icon="pi pi-check-circle" />
            <StatsCard title="Barang Rusak" :value="14" icon="pi pi-exclamation-triangle" />
        </div>
    </DashboardLayout>
</template>
