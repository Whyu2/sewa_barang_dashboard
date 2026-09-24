<script setup>

import DashboardLayout from "@/inertia/Components/DashboardLayout.vue";
import {onMounted} from "vue";
import useAppBreadcrumbStore from "@/inertia/Stores/useAppBreadcrumbStore.js";
import ProductTable from "@/inertia/Modules/MastersProducts/Components/ProductTable.vue";
const store = useAppBreadcrumbStore();

onMounted(() => {
    store.setItem([
        {
            label: 'Data Master',
        },
        {
            label: 'Produk'
        },
    ])
})

</script>

<template>
    <DashboardLayout  use-breadcrumb>
        <div class="flex justify-start ">
            <p class="text-xl font-bold">Produk</p>
        </div>
        <p class="text-sm text-gray-500 mb-4">Kelola katalog barang sewaan beserta foto, QR, dan stok per wilayah.</p>
        <ProductTable />
    </DashboardLayout>
</template>
