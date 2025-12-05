<script setup >
import DataTable from 'primevue/datatable';
import Column from 'primevue/column';
import masterProductUseQuery from "@/inertia/Modules/MastersProducts/Composables/masterProductUseQuery.js";

const {useFetchProductPaginated} = masterProductUseQuery();

const { data:  products, } = useFetchProductPaginated();

</script>

<template>
    <div class="card">
        <DataTable :value="products.data" paginator :rows="5" :rowsPerPageOptions="[5, 10, 20, 50]" tableStyle="min-width: 50rem">
            <Column field="name" header="Name" style="width: 25%"></Column>
            <Column field="category.name" header="Category" style="width: 25%"></Column>
            <Column field="region.name" header="Region" style="width: 25%"></Column>
        </DataTable>
    </div>
</template>

<style scoped>

</style>
