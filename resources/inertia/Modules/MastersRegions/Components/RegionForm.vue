<script setup>
import {inject, ref, onMounted, watch} from 'vue';
import { useToast } from 'primevue/usetoast';
import { Form } from '@primevue/forms';
import { yupResolver } from '@primevue/forms/resolvers/yup';
import * as yup from "yup";
import useMutation from "@/inertia/Modules/MastersRegions/Composables/UseMutation.js";
import { parseApiError } from "@/inertia/Utils/parseApiError.js";

const props = defineProps({
    isUpdate: {
        type: Boolean,
        default: false,
    },
    region: {
        type: Object,
        default: null,
    },
})
const toast = useToast();
const dialogRef = inject('dialogRef');

const {useCreateRegion, useUpdateRegion} = useMutation()
const {mutate: createRegionMutation} = useCreateRegion({
        onSuccess: () => {
            toast.add({ severity: 'success', summary: 'Data berhasil disimpan', life: 2500 });
            dialogRef.value.close();
        },
        onError: showError,
    }
)

const {mutate: updateRegionMutation} = useUpdateRegion({
        onSuccess: () => {
            toast.add({ severity: 'success', summary: 'Data berhasil diperbarui', life: 2500 });
            dialogRef.value.close();
        },
        onError: showError,
    }
)

const serverErrors = ref({});
const showError = (error) => {
    const { message, detailText, fieldErrors } = parseApiError(error, 'Gagal menyimpan wilayah');
    serverErrors.value = fieldErrors;
    toast.add({ severity: 'error', summary: message, detail: detailText, life: 4000 });
};

const initialValues = ref({
    name: '',
    description: '',
});

const resolver = yupResolver(
    yup.object({
        name: yup.string().required('Nama wajib diisi'),
        description: yup.string().nullable(),
    })
);

const onFormSubmit = ({ valid, values }) => {
    console.log(values);
    if (!valid) {
        return;
    }
    serverErrors.value = {};
    const id = props?.region?.id;
    const payload = {
        name: values.name,
        description: values.description,
    }
    if(id) {
        updateRegionMutation( {id:id, payload})
    } else {
        createRegionMutation({payload})
    }

};


watch(
    () => props.region,
    newValue => {
        if (!newValue) {
            return;
        }
        initialValues.value = newValue;
    },
    {
        immediate: true,
    }
);
</script>

<template>
    <div class="flex flex-col gap-4">
        <Form v-slot="$form" :initialValues="initialValues" :resolver="resolver" @submit="onFormSubmit">
            <div class="rounded-2xl border border-surface-200 dark:border-surface-700 p-4 flex flex-col gap-3">
            <div>
                <label class="text-xs text-surface-500">Nama</label>
                    <InputText name="name" placeholder="cth: Jakarta Timur" class="w-full mt-1" />
                    <Message
                        v-if="$form.name?.invalid"
                        severity="error"
                        variant="simple"
                        size="small"
                    >
                        {{ $form.name.error?.message }}
                    </Message>
                    <Message
                        v-if="!$form.name?.invalid && serverErrors.name"
                        severity="error"
                        variant="simple"
                        size="small"
                    >
                        {{ serverErrors.name }}
                    </Message>
            </div>

            <div>
                <label class="text-xs text-surface-500">Deskripsi</label>
                <InputText name="description" placeholder="cth: Gudang utama" class="w-full mt-1" />
                <Message
                    v-if="$form.description?.invalid"
                    severity="error"
                    size="small"
                    variant="simple"
                >
                        {{ $form.description.error?.message }}
                    </Message>
                    <Message
                        v-if="!$form.description?.invalid && serverErrors.description"
                        severity="error"
                        size="small"
                        variant="simple"
                    >
                        {{ serverErrors.description }}
                    </Message>
            </div>
            </div>
            <div class="flex justify-end gap-2">
                <Button label="Batal" severity="secondary" outlined @click="dialogRef.close({ action: 'cancel' })" />
                <Button type="submit" :label="`${props?.isUpdate ? 'Perbarui' : 'Simpan' }`" icon="pi pi-check" />
            </div>
        </Form>
    </div>
</template>
