<script setup >
import DataTable from 'primevue/datatable';
import Column from 'primevue/column';
import useQuery from "@/inertia/Modules/MastersRegions/Composables/UseQuery.js";
import {baseDialog} from "@/inertia/Composables/BaseDialog.js";
import { useToast } from "primevue/usetoast";
import confirmDialog from "@/inertia/Composables/ConfirmDialog.js";
import useMutation from "@/inertia/Modules/MastersRegions/Composables/UseMutation.js";
import useInvalidateQuery from "@/inertia/Modules/MastersRegions/Composables/UseInvalidateQuery.js";
import RegionForm from "@/inertia/Modules/MastersRegions/Components/RegionForm.vue";


const {useDeleteRegion} = useMutation();
const {useInvalidateFetchRegionPaginated} = useInvalidateQuery();
const toast = useToast();
const { mutate: deleteRegion } = useDeleteRegion({
    onSuccess: async () => {
        await useInvalidateFetchRegionPaginated();
        toast.add({ severity: 'success', summary: 'Success', life: 2500 });    },
});

const {useFetchRegionPaginated} = useQuery();
const { data:  region } = useFetchRegionPaginated();
const { openBaseDialog } = baseDialog();
const {baseConfirmDialog} = confirmDialog();
const handleOpenDialogAdd = () => {
    openBaseDialog(
        {
            titleHeader: 'Add New Region',
        component: RegionForm,
        width: `50vw`,
        componentProps: {
            isUpdate: false,
        },
    });
}

const handleOpenDialogUpdate = (region) => {
    openBaseDialog(
        {
            titleHeader: 'Update Region',
            component: RegionForm,
            width: `50vw`,
            componentProps: {
                region: region,
                isUpdate: true,
            },
        });
}
const confirmDelete = (id) => {
    baseConfirmDialog({
        message: 'Do you want to delete this record?',
        header: 'Delete Confirmation',
        acceptLabel: 'Delete',
        onAccept: () => {
            deleteRegion({id});
        },
    });
};

</script>

<template>
    <div class="card">
        <div class="flex justify-end mb-4 ">
         <Button label="Add New" @click="handleOpenDialogAdd" icon="pi pi-plus"/>
        </div>
        <DataTable v-if="region" :value="region.data" paginator :rows="5" :rowsPerPageOptions="[5, 10, 20, 50]" tableStyle="min-width: 50rem" show-gridlines>
            <Column field="name" header="Name" style="width: 25%"></Column>
            <Column field="description" header="Description" style="width: 25%"></Column>
            <Column field="action" header="Action" style="width: 5%">
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
