<script setup>

import DashboardLayout from "@/inertia/Components/DashboardLayout.vue";
import {onMounted} from "vue";
import useAppBreadcrumbStore from "@/inertia/Stores/useAppBreadcrumbStore.js";
import UserTable from "@/inertia/Modules/MastersUsers/Components/UserTable.vue";
const store = useAppBreadcrumbStore();

onMounted(() => {
    store.setItem([
        {
            label: 'Data Master',
        },
        {
            label: 'Pengguna'
        },
    ])
})

</script>

<template>

    <DashboardLayout  use-breadcrumb>
        <div class="flex justify-start">
            <p class="text-xl font-bold">Pengguna</p>
        </div>
        <p class="text-sm text-gray-500 mb-4">Kelola akun pengguna beserta peran dan wilayahnya.</p>
        <UserTable />
    </DashboardLayout>
</template>
