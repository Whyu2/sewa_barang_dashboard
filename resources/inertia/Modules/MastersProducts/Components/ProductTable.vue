<script setup >
import DataTable from 'primevue/datatable';
import Column from 'primevue/column';
import useQuery from "@/inertia/Modules/MastersProducts/Composables/UseQuery.js";
import {baseDialog} from "@/inertia/Composables/BaseDialog.js";
import { useToast } from "primevue/usetoast";
import confirmDialog from "@/inertia/Composables/ConfirmDialog.js";
import useMutation from "@/inertia/Modules/MastersProducts/Composables/UseMutation.js";
import ProductForm from "@/inertia/Modules/MastersProducts/Components/ProductForm.vue";
import useInvalidateQuery from "@/inertia/Modules/MastersProducts/Composables/UseInvalidateQuery.js";
import ProductDetail from "@/inertia/Modules/MastersProducts/Components/ProductDetail.vue";


const {useDeleteProduct} = useMutation();
const {useInvalidateFetchProductPaginated} = useInvalidateQuery();
const toast = useToast();
const { mutate: deleteProduct } = useDeleteProduct({
    onSuccess: async () => {
        await useInvalidateFetchProductPaginated();
        toast.add({ severity: 'success', summary: 'Success', life: 2500 });    },
});

const {useFetchProductPaginated} = useQuery();
const { data:  product } = useFetchProductPaginated();
const { openBaseDialog } = baseDialog();
const {baseConfirmDialog} = confirmDialog();
const handleOpenDialogAdd = () => {
    openBaseDialog(
        {
            titleHeader: 'Add New Product',
        component: ProductForm,
        width: `50vw`,
        componentProps: {
            isUpdate: false,
        },
    });
}

const handleOpenDialogUpdate = (product) => {
    openBaseDialog(
        {
            titleHeader: 'Update Product',
            component: ProductForm,
            width: `50vw`,
            componentProps: {
                product: product,
                isUpdate: true,
            },
        });
}

const handleOpenDialogDetail = (product) => {
    openBaseDialog(
        {
            titleHeader: 'Detail Product',
            component: ProductDetail,
            width: `70vw`,
            componentProps: {
                product: product,
            },
        });
}
const confirmDelete = (id) => {
    baseConfirmDialog({
        message: 'Do you want to delete this record?',
        header: 'Delete Confirmation',
        acceptLabel: 'Delete',
        onAccept: () => {
            deleteProduct({id});
        },
    });
};

</script>

<template>
    <div class="card">
        <div class="flex justify-end mb-4 ">
         <Button label="Add New" @click="handleOpenDialogAdd" icon="pi pi-plus"/>
        </div>
        <DataTable v-if="product" :value="product.data" paginator :rows="5" :rowsPerPageOptions="[5, 10, 20, 50]" tableStyle="min-width: 50rem" show-gridlines>
            <Column field="name" header="Name" style="width: 25%"></Column>
            <Column field="category.name" header="Category" style="width: 25%"></Column>
            <Column field="qty" header="QTY"></Column>
            <Column field="rent_price" header="Rent Price"></Column>
            <Column field="action" header="Action">
                <template #body="slotProps">
                    <Button icon="pi pi-pencil" rounded text size="small" @click="handleOpenDialogUpdate(slotProps.data)"/>
                    <Button icon="pi pi-trash" rounded text size="small" @click="confirmDelete(slotProps.data.id)" />
                    <Button icon="pi pi-eye" rounded text size="small" @click="handleOpenDialogDetail(slotProps.data)" />
                </template>
            </Column>
        </DataTable>
    </div>
</template>

<style scoped>

</style>
