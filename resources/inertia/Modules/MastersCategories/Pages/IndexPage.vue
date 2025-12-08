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
            label: 'Category Product'
        },
    ])
})

</script>

<template>

    <DashboardLayout  use-breadcrumb>
        <div class="flex justify-start mb-4 ">
            <p class="text-xl font-bold">Category Product</p>
        </div>
        <CategoryTable />
    </DashboardLayout>
</template>
