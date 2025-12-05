<script setup >
import DataTable from 'primevue/datatable';
import Column from 'primevue/column';
import masterProductUseQuery from "@/inertia/Modules/MastersProducts/Composables/masterProductUseQuery.js";
import {baseDialog} from "@/inertia/Composables/BaseDialog.js";
import ProductForm from "@/inertia/Modules/MastersProducts/Components/ProductForm.vue";

const {useFetchProductPaginated} = masterProductUseQuery();

const { data:  products, } = useFetchProductPaginated();

const { openBaseDialog } = baseDialog();

const handleOpenDialog = () => {
    openBaseDialog({
        component: ProductForm,
        componentProps: {
            product: null,
        },
    });
}

</script>

<template>
    <div class="card">
        <DataTable :value="products.data" paginator :rows="5" :rowsPerPageOptions="[5, 10, 20, 50]" tableStyle="min-width: 50rem" show-gridlines>
            <Column field="name" header="Name" style="width: 25%"></Column>
            <Column field="category.name" header="Category" style="width: 25%"></Column>
            <Column field="region.name" header="Region" style="width: 25%"></Column>
            <Column field="action" header="Action" style="width: 25%">
                <template #body="slotProps">
                    <Button icon="pi pi-eye" rounded text size="small" @click="handleOpenDialog"/>
                    <Button icon="pi pi-pencil" rounded text size="small" />
                    <Button icon="pi pi-trash" rounded text size="small" />

                </template>
            </Column>
        </DataTable>
    </div>
</template>

<style scoped>

</style>
