<script setup>
import DashboardLayout from '@/inertia/Components/DashboardLayout.vue';
import { onMounted } from 'vue';
import useAppBreadcrumbStore from '@/inertia/Stores/useAppBreadcrumbStore.js';
import TransactionTable from '@/inertia/Modules/Transactions/Components/TransactionTable.vue';
const store = useAppBreadcrumbStore();
onMounted(() => store.setItem([{ label: 'Transaksi' }]));
</script>
<template>
    <DashboardLayout use-breadcrumb>
        <div class="flex justify-start"><p class="text-xl font-bold">Transaksi</p></div>
        <p class="text-sm text-gray-500 mb-4">Dipantau dari dashboard — data dibuat via mobile scan QR. Dashboard hanya Ubah & Hapus.</p>
        <TransactionTable />
    </DashboardLayout>
</template>
