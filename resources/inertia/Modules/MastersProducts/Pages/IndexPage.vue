<script setup>

import DashboardLayout from "@/inertia/Components/DashboardLayout.vue";
import ProductTable from "@/inertia/Modules/MastersProducts/Components/ProductTable.vue";
import {onMounted} from "vue";
import useAppBreadcrumbStore from "@/inertia/Stores/useAppBreadcrumbStore.js";
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
        <ProductTable />
    </DashboardLayout>
</template>
