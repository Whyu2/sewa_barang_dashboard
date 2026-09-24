<script setup >
import DataTable from 'primevue/datatable';
import Column from 'primevue/column';
import useQuery from "@/inertia/Modules/MastersUsers/Composables/UseQuery.js";
import {baseDialog} from "@/inertia/Composables/BaseDialog.js";
import { useToast } from "primevue/usetoast";
import confirmDialog from "@/inertia/Composables/ConfirmDialog.js";
import useMutation from "@/inertia/Modules/MastersUsers/Composables/UseMutation.js";
import useInvalidateQuery from "@/inertia/Modules/MastersUsers/Composables/UseInvalidateQuery.js";
import UserForm from "@/inertia/Modules/MastersUsers/Components/UserForm.vue";
import { parseApiError } from "@/inertia/Utils/parseApiError.js";
import { USER_ROLES } from "@/inertia/Enums/UserRole.js";

const isAdminRow = (row) => row?.role === USER_ROLES.ADMIN;


const {useDeleteUser} = useMutation();
const {useInvalidateFetchUserPaginated} = useInvalidateQuery();
const toast = useToast();
const { mutate: deleteUser } = useDeleteUser({
    onSuccess: async () => {
        await useInvalidateFetchUserPaginated();
        toast.add({ severity: 'success', summary: 'Success', life: 2500 });    },
    onError: (error) => {
        const { message, detailText } = parseApiError(error, 'Gagal menghapus user');
        toast.add({ severity: 'error', summary: message, detail: detailText, life: 4000 });
    },
});

const {useFetchUserPaginated} = useQuery();
const { data:  user } = useFetchUserPaginated();
const { openBaseDialog } = baseDialog();
const {baseConfirmDialog} = confirmDialog();
const handleOpenDialogAdd = () => {
    openBaseDialog(
        {
            titleHeader: 'Add New User',
        component: UserForm,
        width: `50vw`,
        componentProps: {
            isUpdate: false,
        },
    });
}

const handleOpenDialogUpdate = (user) => {
    openBaseDialog(
        {
            titleHeader: 'Update User',
            component: UserForm,
            width: `50vw`,
            componentProps: {
                user: user,
                isUpdate: true,
            },
        });
}
const confirmDelete = (id) => {
    baseConfirmDialog({
        message: 'Hapus user ini? Data akan dihapus dari daftar.',
        header: 'Delete Confirmation',
        acceptLabel: 'Delete',
        onAccept: () => {
            deleteUser({id});
        },
    });
};

</script>

<template>
    <div class="card">
        <div class="flex justify-end mb-4 ">
         <Button label="Add New" @click="handleOpenDialogAdd" icon="pi pi-plus"/>
        </div>
        <DataTable v-if="user" :value="user.data" paginator :rows="5" :rowsPerPageOptions="[5, 10, 20, 50]" tableStyle="min-width: 50rem" show-gridlines>
            <Column field="name" header="Name" style="width: 20%"></Column>
            <Column field="email" header="Email" style="width: 25%"></Column>
            <Column field="role" header="Role" style="width: 10%">
                <template #body="slotProps">
                    <Tag :value="slotProps.data.role" :severity="isAdminRow(slotProps.data) ? 'danger' : 'info'" />
                </template>
            </Column>
            <Column field="region.name" header="Region" style="width: 20%"></Column>
            <Column field="action" header="Action" style="width: 5%">
                <template #body="slotProps">
                    <Button icon="pi pi-pencil" rounded text size="small" @click="handleOpenDialogUpdate(slotProps.data)"/>
                    <Button v-if="!isAdminRow(slotProps.data)" icon="pi pi-trash" rounded text size="small" @click="confirmDelete(slotProps.data.id)" />
                </template>
            </Column>
        </DataTable>
    </div>
</template>

<style scoped>

</style>
