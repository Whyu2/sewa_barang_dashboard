<script setup>

import DashboardLayout from "@/inertia/Modules/Dashboard/Layouts/DashboardLayout.vue";
import StatsCard from "@/inertia/Modules/Dashboard/Components/StatsCard.vue";
import LoaderOverlay from "@/inertia/Components/LoaderOverlay.vue";
import dashboardUseQuery from "@/inertia/Modules/Dashboard/Composables/dashboardUseQuery.js";
import {computed} from "vue";


const {useFetchProducts} = dashboardUseQuery();
const { data:  products, } = useFetchProducts();

const totalProducts = computed(() => {
    return products.value.length;
})
</script>

<template>
    <DashboardLayout>

        <LoaderOverlay />
        <div class="grid grid-cols-4 gap-4 mb-6">
            <StatsCard title="Total Barang" :value="totalProducts" icon="pi pi-box" />
            <StatsCard title="Sedang Dipinjam" value="24" icon="pi pi-shopping-cart" />
            <StatsCard title="Tersedia" value="24" icon="pi pi-check-circle" />
            <StatsCard title="Barang Rusak" value="14" icon="pi pi-exclamation-triangle" />
        </div>
    </DashboardLayout>
</template>
