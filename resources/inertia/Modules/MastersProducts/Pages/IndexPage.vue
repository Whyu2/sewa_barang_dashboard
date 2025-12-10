<script setup>

import DashboardLayout from "@/inertia/Components/DashboardLayout.vue";
import {onMounted} from "vue";
import useAppBreadcrumbStore from "@/inertia/Stores/useAppBreadcrumbStore.js";
import ProductTable from "@/inertia/Modules/MastersProducts/Components/ProductTable.vue";
const store = useAppBreadcrumbStore();

onMounted(() => {
    store.setItem([
        {
            label: 'Master',
        },
        {
            label: 'Product'
        },
    ])
})

</script>

<template>
    <DashboardLayout  use-breadcrumb>
        <div class="flex justify-start ">
            <p class="text-xl font-bold">Product</p>
        </div>
        <ProductTable />
    </DashboardLayout>
</template>
