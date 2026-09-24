<script setup>
import DashboardLayout from '@/inertia/Components/DashboardLayout.vue';
import { onMounted } from 'vue';
import useAppBreadcrumbStore from '@/inertia/Stores/useAppBreadcrumbStore.js';
import useQuery from '@/inertia/Modules/Logs/Composables/UseQuery.js';
import LogTable from '@/inertia/Modules/Logs/Components/LogTable.vue';
const store = useAppBreadcrumbStore();
onMounted(()=>store.setItem([{label:'Log Transaksi'}]));
const { useFetchLogsPaginated } = useQuery();
const { data: logs } = useFetchLogsPaginated({ limit: 10 });
</script>
<template>
  <DashboardLayout use-breadcrumb>
    <div class="flex justify-between items-center mb-4"><p class="text-xl font-bold">Log Transaksi</p></div>
    <LogTable :logs="logs" />
  </DashboardLayout>
</template>
