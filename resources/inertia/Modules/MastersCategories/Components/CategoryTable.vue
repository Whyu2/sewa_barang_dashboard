<script setup >
import DataTable from 'primevue/datatable';
import Column from 'primevue/column';
import useQuery from "@/inertia/Modules/MastersCategories/Composables/UseQuery.js";
import {baseDialog} from "@/inertia/Composables/BaseDialog.js";
import CategoryForm from "@/inertia/Modules/MastersCategories/Components/CategoryForm.vue";
import { useToast } from "primevue/usetoast";
import confirmDialog from "@/inertia/Composables/ConfirmDialog.js";
import useMutation from "@/inertia/Modules/MastersCategories/Composables/UseMutation.js";
import useInvalidateQuery from "@/inertia/Modules/MastersCategories/Composables/UseInvalidateQuery.js";
import { parseApiError } from "@/inertia/Utils/parseApiError.js";


const {useDeleteCategory} = useMutation();
const {useInvalidateFetchCategoryPaginated} = useInvalidateQuery();
const toast = useToast();
const { mutate: deleteCategory } = useDeleteCategory({
    onSuccess: async () => {
        await useInvalidateFetchCategoryPaginated();
        toast.add({ severity: 'success', summary: 'Data berhasil dihapus', life: 2500 });    },
    onError: (error) => {
        const { message, detailText } = parseApiError(error, 'Gagal menghapus kategori');
        toast.add({ severity: 'error', summary: message, detail: detailText, life: 4000 });
    },
});

const {useFetchCategoryPaginated} = useQuery();
const { data:  category } = useFetchCategoryPaginated();
const { openBaseDialog } = baseDialog();
const {baseConfirmDialog} = confirmDialog();
const handleOpenDialogAdd = () => {
    openBaseDialog(
        {
            titleHeader: 'Tambah Kategori Baru',
        component: CategoryForm,
        width: `50vw`,
        componentProps: {
            isUpdate: false,
        },
    });
}

const handleOpenDialogUpdate = (category) => {
    openBaseDialog(
        {
            titleHeader: 'Ubah Kategori',
            component: CategoryForm,
            width: `50vw`,
            componentProps: {
                category: category,
                isUpdate: true,
            },
        });
}
const confirmDelete = (id) => {
    baseConfirmDialog({
        message: 'Hapus data ini? Tindakan ini tidak dapat dibatalkan.',
        header: 'Konfirmasi Hapus',
        acceptLabel: 'Hapus',
        onAccept: () => {
            deleteCategory({id});
        },
    });
};

</script>

<template>
    <div class="card">
        <div class="flex justify-end mb-4 ">
         <Button label="Tambah Data" @click="handleOpenDialogAdd" icon="pi pi-plus"/>
        </div>
        <DataTable v-if="category" :value="category.data" paginator :rows="5" :rowsPerPageOptions="[5, 10, 20, 50]" tableStyle="min-width: 50rem" show-gridlines>
            <Column field="name" header="Nama" style="width: 25%"></Column>
            <Column field="description" header="Deskripsi" style="width: 25%"></Column>
            <Column field="action" header="Aksi" style="width: 5%">
                <template #body="slotProps">
                    <Button icon="pi pi-pencil" rounded text size="small" @click="handleOpenDialogUpdate(slotProps.data)"/>
                    <Button icon="pi pi-trash" rounded text size="small" @click="confirmDelete(slotProps.data.id)" />
                </template>
            </Column>
        </DataTable>
    </div>
</template>

<style scoped>

</style>
