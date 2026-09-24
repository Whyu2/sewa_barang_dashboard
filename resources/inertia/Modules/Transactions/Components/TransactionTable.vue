<script setup>
import DataTable from 'primevue/datatable';
import Column from 'primevue/column';
import Tag from 'primevue/tag';
import useQuery from '@/inertia/Modules/Transactions/Composables/UseQuery.js';
import { baseDialog } from '@/inertia/Composables/BaseDialog.js';
import confirmDialog from '@/inertia/Composables/ConfirmDialog.js';
import useMutation from '@/inertia/Modules/Transactions/Composables/UseMutation.js';
import useInvalidateQuery from '@/inertia/Modules/Transactions/Composables/UseInvalidateQuery.js';
import { useToast } from 'primevue/usetoast';
import TransactionForm from '@/inertia/Modules/Transactions/Components/TransactionForm.vue';
import TransactionDetail from '@/inertia/Modules/Transactions/Components/TransactionDetail.vue';
import { formatIDR } from '@/inertia/Utils/formatIDR.js';
import { formatDateID } from '@/inertia/Utils/formatDate.js';
import { getStatusSeverity, getStatusLabel } from '@/inertia/Utils/statusBadge.js';
import { formatTRX } from '@/inertia/Utils/formatTRX.js';
import { formatDurationDays } from '@/inertia/Utils/rentalDuration.js';
import { parseApiError } from '@/inertia/Utils/parseApiError.js';

const toast = useToast();
const { useFetchTransactionsPaginated } = useQuery();
const { data: tx } = useFetchTransactionsPaginated();
const { openBaseDialog } = baseDialog();
const { baseConfirmDialog } = confirmDialog();
const { useDeleteTransaction } = useMutation();
const { useInvalidateFetchTransactionsPaginated } = useInvalidateQuery();

const { mutate: delTx } = useDeleteTransaction({
    onSuccess: async () => {
        await useInvalidateFetchTransactionsPaginated();
        toast.add({ severity: 'success', summary: 'Deleted', life: 2500 });
    },
    onError: (e) => {
        const { message, detailText } = parseApiError(e, 'Gagal menghapus transaction');
        toast.add({ severity: 'error', summary: message, detail: detailText, life: 4000 });
    },
});

const openDetail = (row) => openBaseDialog({ titleHeader: 'Detail Transaction', component: TransactionDetail, width: '60vw', componentProps: { transaction: row } });
const openEdit = (row) => openBaseDialog({ titleHeader: 'Edit Transaction', component: TransactionForm, width: '50vw', componentProps: { transaction: row } });
const confirmDelete = (id) => baseConfirmDialog({ message: 'Delete this transaction?', header: 'Delete Confirmation', acceptLabel: 'Delete', onAccept: () => delTx({ id }) });

</script>
<template>
    <div class="card">
        <DataTable v-if="tx" :value="tx.data" paginator :rows="10" :rowsPerPageOptions="[10,20,50]" tableStyle="min-width: 70rem" showGridlines>
            <Column header="TRX ID" style="width:6rem"><template #body="{data}"><span class="font-mono text-xs">{{ formatTRX(data.id) }}</span></template></Column>
            <Column header="Product">
                <template #body="{ data }">{{ data.product?.name ?? '-' }}</template>
            </Column>
            <Column header="Region">
                <template #body="{ data }">{{ data.region?.name ?? '-' }}</template>
            </Column>
            <Column field="renter_name" header="Penyewa" />
            <Column field="qty" header="Qty" style="width:5rem" />
            <Column header="Harga">
                <template #body="{ data }">{{ formatIDR(data.rent_price) }}</template>
            </Column>
            <Column header="Tgl Sewa">
                <template #body="{ data }">{{ formatDateID(data.rent_date) }}</template>
            </Column>
            <Column header="Tgl Perkiraan Pengembalian">
                <template #body="{ data }">{{ formatDateID(data.expected_return_date) }}</template>
            </Column>
            <Column header="Lama Sewa" style="width:7rem">
                <template #body="{ data }">{{ formatDurationDays(data.rent_date, data.expected_return_date) }}</template>
            </Column>
            <Column header="Tgl Pengembalian">
                <template #body="{ data }">{{ formatDateID(data.return_date) }}</template>
            </Column>
            <Column header="Status">
                <template #body="{ data }"><Tag :value="getStatusLabel(data.status)" :severity="getStatusSeverity(data.status)" /></template>
            </Column>
            <Column header="Action" style="width:10rem">
                <template #body="{ data }">
                    <Button icon="pi pi-eye" rounded text size="small" @click="openDetail(data)" />
                    <Button icon="pi pi-pencil" rounded text size="small" @click="openEdit(data)" />
                    <Button icon="pi pi-trash" rounded text size="small" severity="danger" @click="confirmDelete(data.id)" />
                </template>
            </Column>
        </DataTable>
    </div>
</template>
