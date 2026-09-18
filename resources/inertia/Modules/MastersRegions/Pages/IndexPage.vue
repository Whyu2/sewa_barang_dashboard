<script setup>

import DashboardLayout from "@/inertia/Components/DashboardLayout.vue";
import {onMounted} from "vue";
import useAppBreadcrumbStore from "@/inertia/Stores/useAppBreadcrumbStore.js";
import RegionTable from "@/inertia/Modules/MastersRegions/Components/RegionTable.vue";
const store = useAppBreadcrumbStore();

onMounted(() => {
    store.setItem([
        {
            label: 'Master',
        },
        {
            label: 'Region'
        },
    ])
})

</script>

<template>

    <DashboardLayout  use-breadcrumb>
        <div class="flex justify-start">
            <p class="text-xl font-bold">Region</p>
        </div>
        <p class="text-sm text-gray-500 mb-4">Kelola lokasi/wilayah penempatan stok barang.</p>
        <RegionTable />
    </DashboardLayout>
</template>
