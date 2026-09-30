<script setup>
import DataTable from 'primevue/datatable';
import Column from 'primevue/column';
import { formatDateID } from '@/inertia/Utils/formatDate.js';
import { getStatusSeverity, getStatusLabel } from '@/inertia/Utils/statusBadge.js';
import { formatTRX } from '@/inertia/Utils/formatTRX.js';
defineProps({ logs: { type: Object, default: () => ({}) } });
</script>
<template>
  <div class="max-w-full overflow-hidden">
  <div class="overflow-x-auto max-w-full">
  <DataTable :value="logs?.data || logs || []" paginator :rows="10" :rowsPerPageOptions="[10,20,50]" size="small" scrollable tableStyle="min-width:70rem" showGridlines emptyMessage="Tidak ada log">
    <Column header="ID Transaksi" style="min-width:7rem"><template #body="{data}"><span class="font-mono text-xs whitespace-nowrap">{{ formatTRX(data.transaction_id ?? data.transaction?.id) }}</span></template></Column>
    <Column header="Waktu" style="min-width:10rem"><template #body="{data}"><span class="whitespace-nowrap">{{ formatDateID(data.created_at) }}</span></template></Column>
    <Column header="Produk" style="min-width:12rem"><template #body="{data}"><span class="whitespace-nowrap">{{ data.product?.name ?? data.transaction?.product?.name ?? '-' }}</span></template></Column>
    <Column header="Aksi" style="min-width:8rem"><template #body="{data}"><Tag :value="getStatusLabel(data.action)" :severity="getStatusSeverity(data.action)" /></template></Column>
    <Column header="Perubahan Status" style="min-width:14rem"><template #body="{data}"><span class="whitespace-nowrap">{{ getStatusLabel(data.from_status) ?? '-' }} → {{ getStatusLabel(data.to_status ?? data.action) }}</span></template></Column>
    <Column header="Pengguna" style="min-width:9rem"><template #body="{data}"><span class="whitespace-nowrap">{{ data.user?.name ?? '-' }}</span></template></Column>
  </DataTable>
  </div>
  </div>
</template>
