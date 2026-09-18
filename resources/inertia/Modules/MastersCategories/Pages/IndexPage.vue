<script setup>

import DashboardLayout from "@/inertia/Components/DashboardLayout.vue";
import {onMounted} from "vue";
import useAppBreadcrumbStore from "@/inertia/Stores/useAppBreadcrumbStore.js";
import CategoryTable from "@/inertia/Modules/MastersCategories/Components/CategoryTable.vue";
const store = useAppBreadcrumbStore();

onMounted(() => {
    store.setItem([
        {
            label: 'Master',
        },
        {
            label: 'Category'
        },
    ])
})

</script>

<template>

    <DashboardLayout  use-breadcrumb>
        <div class="flex justify-start">
            <p class="text-xl font-bold">Category</p>
        </div>
        <p class="text-sm text-gray-500 mb-4">Kelola kategori barang sewaan — kategori dipakai saat menambah produk.</p>
        <CategoryTable />
    </DashboardLayout>
</template>
