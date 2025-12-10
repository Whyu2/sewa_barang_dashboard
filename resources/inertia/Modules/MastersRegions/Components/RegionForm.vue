<script setup>
import {inject, ref, onMounted, watch} from 'vue';
import { useToast } from 'primevue/usetoast';
import { Form } from '@primevue/forms';
import { yupResolver } from '@primevue/forms/resolvers/yup';
import * as yup from "yup";
import useMutation from "@/inertia/Modules/MastersRegions/Composables/UseMutation.js";
import useInvalidateQuery from "@/inertia/Modules/MastersRegions/Composables/UseInvalidateQuery.js";

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
const {useInvalidateFetchRegionPaginated} = useInvalidateQuery();
const {mutate: createRegionMutation} = useCreateRegion({
        onSuccess:async () => {
            await useInvalidateFetchRegionPaginated();
            toast.add({ severity: 'success', summary: 'Success', life: 2500 });
            dialogRef.value.close();
        }
    }
)

const {mutate: updateRegionMutation} = useUpdateRegion({
        onSuccess:async () => {
            await useInvalidateFetchRegionPaginated();
            toast.add({ severity: 'success', summary: 'Success', life: 2500 });
            dialogRef.value.close();
        }
    }
)

const initialValues = ref({
    name: '',
    description: '',
});

const resolver = yupResolver(
    yup.object({
        name: yup.string().required(),
        description: yup.string().nullable(),
    })
);

const onFormSubmit = ({ valid, values }) => {
    console.log(values);
    if (!valid) {
        return;
    }
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
    <div class="card">
        <Form v-slot="$form" :initialValues="initialValues" :resolver="resolver" @submit="onFormSubmit">
            <div class="mb-2">
                <label for="name">Name</label>
                    <InputText name="name" placeholder="Name"     class="w-full" />
                    <Message
                        v-if="$form.name?.invalid"
                        severity="error"
                        variant="simple"
                        size="small"
                    >
                        {{ $form.name.error?.message }}
                    </Message>
            </div>

            <div class="mb-2">
                <label for="description">Description</label>
                <InputText name="description" placeholder="Description" class="w-full" />
                <Message
                    v-if="$form.description?.invalid"
                    severity="error"
                    size="small"
                    variant="simple"
                >
                    {{ $form.description.error?.message }}
                </Message>
            </div>
            <div class="flex justify-end">
                <Button type="submit" :label="`${props?.isUpdate ? 'Update' : 'Submit' }`" icon="pi pi-send" class="mt-4" />
            </div>
        </Form>
    </div>
</template>
