<script setup>
import DataTable from 'primevue/datatable';
import Column from 'primevue/column';
import { formatDateID } from '@/inertia/Utils/formatDate.js';
import { getStatusSeverity, getStatusLabel } from '@/inertia/Utils/statusBadge.js';
import { formatTRX } from '@/inertia/Utils/formatTRX.js';
defineProps({ logs: { type: Object, default: () => ({}) } });
</script>
<template>
  <DataTable :value="logs?.data || logs || []" paginator :rows="10" :rowsPerPageOptions="[10,20,50]" size="small" tableStyle="min-width:60rem" showGridlines emptyMessage="Tidak ada log">
    <Column header="TRX ID" style="width:6rem"><template #body="{data}"><span class="font-mono text-xs">{{ formatTRX(data.transaction_id ?? data.transaction?.id) }}</span></template></Column>
    <Column header="Waktu"><template #body="{data}">{{ formatDateID(data.created_at) }}</template></Column>
    <Column header="Produk"><template #body="{data}">{{ data.product?.name ?? data.transaction?.product?.name ?? '-' }}</template></Column>
    <Column header="Action"><template #body="{data}"><Tag :value="getStatusLabel(data.action)" :severity="getStatusSeverity(data.action)" /></template></Column>
    <Column header="From→To"><template #body="{data}">{{ getStatusLabel(data.from_status) ?? '-' }} → {{ getStatusLabel(data.to_status ?? data.action) }}</template></Column>
    <Column header="User"><template #body="{data}">{{ data.user?.name ?? '-' }}</template></Column>
  </DataTable>
</template>
