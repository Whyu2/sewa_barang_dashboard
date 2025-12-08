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


const {useDeleteCategory} = useMutation();
const {useInvalidateFetchCategoryPaginated} = useInvalidateQuery();
const toast = useToast();
const { mutate: deleteCategory } = useDeleteCategory({
    onSuccess: async () => {
        await useInvalidateFetchCategoryPaginated();
        toast.add({ severity: 'success', summary: 'Success', life: 2500 });    },
});

const {useFetchProductPaginated} = useQuery();
const { data:  category } = useFetchProductPaginated();
const { openBaseDialog } = baseDialog();
const {baseConfirmDialog} = confirmDialog();
const handleOpenDialog = () => {
    openBaseDialog(
        {
            titleHeader: 'Add New Category',
        component: CategoryForm,
        width: `50vw`,
        componentProps: {
            product: null,
        },
    });
}
const confirmDelete = (id) => {
    baseConfirmDialog({
        message: 'Do you want to delete this record?',
        header: 'Delete Confirmation',
        acceptLabel: 'Delete',
        onAccept: () => {
            deleteCategory({id});
        },
    });
};

</script>

<template>
    <div class="card">
        <div class="flex justify-end mb-4 ">
         <Button label="Add New" @click="handleOpenDialog" icon="pi pi-plus"/>
        </div>
        <DataTable v-if="category" :value="category.data" paginator :rows="5" :rowsPerPageOptions="[5, 10, 20, 50]" tableStyle="min-width: 50rem" show-gridlines>
            <Column field="name" header="Name" style="width: 25%"></Column>
            <Column field="description" header="Description" style="width: 25%"></Column>
            <Column field="action" header="Action" style="width: 25%">
                <template #body="slotProps">
                    <Button icon="pi pi-eye" rounded text size="small" @click="handleOpenDialog"/>
                    <Button icon="pi pi-pencil" rounded text size="small" />
                    <Button icon="pi pi-trash" rounded text size="small" @click="confirmDelete(slotProps.data.id)" />
                </template>
            </Column>
        </DataTable>
    </div>
</template>

<style scoped>

</style>
