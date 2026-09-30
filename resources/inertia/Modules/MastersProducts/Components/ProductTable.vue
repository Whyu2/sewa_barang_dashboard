<script setup >
import DataTable from 'primevue/datatable';
import Column from 'primevue/column';
import useQuery from "@/inertia/Modules/MastersProducts/Composables/UseQuery.js";
import {baseDialog} from "@/inertia/Composables/BaseDialog.js";
import { useToast } from "primevue/usetoast";
import confirmDialog from "@/inertia/Composables/ConfirmDialog.js";
import useMutation from "@/inertia/Modules/MastersProducts/Composables/UseMutation.js";
import ProductForm from "@/inertia/Modules/MastersProducts/Components/ProductForm.vue";
import ProductDetail from "@/inertia/Modules/MastersProducts/Components/ProductDetail.vue";
import { parseApiError } from "@/inertia/Utils/parseApiError.js";


const {useDeleteProduct} = useMutation();
const toast = useToast();
const { mutate: deleteProduct } = useDeleteProduct({
    onSuccess: async () => {
        toast.add({ severity: 'success', summary: 'Data berhasil dihapus', life: 2500 });    },
    onError: (error) => {
        const { message, detailText } = parseApiError(error, 'Gagal menghapus produk');
        toast.add({ severity: 'error', summary: message, detail: detailText, life: 4000 });
    },
});

const {useFetchProductPaginated} = useQuery();
const { data:  product } = useFetchProductPaginated();
const { openBaseDialog } = baseDialog();
const {baseConfirmDialog} = confirmDialog();
const handleOpenDialogAdd = () => {
    openBaseDialog(
        {
            titleHeader: 'Tambah Produk Baru',
        component: ProductForm,
        width: '640px',
        componentProps: {
            isUpdate: false,
        },
    });
}

const handleOpenDialogUpdate = (product) => {
    openBaseDialog(
        {
            titleHeader: 'Ubah Produk',
            component: ProductForm,
            width: '640px',
            componentProps: {
                product: product,
                isUpdate: true,
            },
        });
}

const handleOpenDialogDetail = (product) => {
    openBaseDialog(
        {
            titleHeader: 'Detail Produk',
            component: ProductDetail,
            width: '720px',
            componentProps: {
                product: product,
            },
        });
}
const confirmDelete = (id) => {
    baseConfirmDialog({
        message: 'Hapus data ini? Tindakan ini tidak dapat dibatalkan.',
        header: 'Konfirmasi Hapus',
        acceptLabel: 'Hapus',
        onAccept: () => {
            deleteProduct({id});
        },
    });
};

</script>

<template>
    <div class="card max-w-full overflow-hidden">
        <div class="flex justify-end mb-4 ">
         <Button label="Tambah Data" @click="handleOpenDialogAdd" icon="pi pi-plus"/>
        </div>
        <div class="overflow-x-auto max-w-full">
        <DataTable v-if="product" :value="product.data" paginator :rows="5" :rowsPerPageOptions="[5, 10, 20, 50]" scrollable tableStyle="min-width: 50rem" show-gridlines>
            <Column field="name" header="Nama" style="min-width:12rem"></Column>
            <Column field="category_name" header="Kategori" style="min-width:10rem"></Column>
            <Column header="Stok per Wilayah" style="min-width:16rem">
                <template #body="slotProps">
                    <div v-if="slotProps.data.product_region?.length" class="flex flex-col gap-1 whitespace-nowrap">
                        <span v-for="region in slotProps.data.product_region" :key="region.region_id" class="text-xs border border-gray-300 rounded px-2 py-1 flex justify-between gap-2">
                            <span>{{ region.region_name }}</span><span class="font-medium">{{ region.qty }}</span>
                        </span>
                    </div>
                    <span v-else class="text-gray-400 text-xs">-</span>
                </template>
            </Column>
            <Column field="action" header="Aksi" frozen alignFrozen="right" style="min-width:8rem">
                <template #body="slotProps">
                    <Button icon="pi pi-pencil" rounded text size="small" @click="handleOpenDialogUpdate(slotProps.data)"/>
                    <Button icon="pi pi-trash" rounded text size="small" @click="confirmDelete(slotProps.data.id)" />
                    <Button icon="pi pi-eye" rounded text size="small" @click="handleOpenDialogDetail(slotProps.data)" />
                </template>
            </Column>
        </DataTable>
        </div>
    </div>
</template>

<style scoped>

</style>
