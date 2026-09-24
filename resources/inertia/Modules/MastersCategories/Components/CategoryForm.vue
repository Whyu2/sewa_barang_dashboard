<script setup>
import {inject, ref, onMounted, watch} from 'vue';
import { useToast } from 'primevue/usetoast';
import { Form } from '@primevue/forms';
import { yupResolver } from '@primevue/forms/resolvers/yup';
import * as yup from "yup";
import useMutation from "@/inertia/Modules/MastersCategories/Composables/UseMutation.js";
import useInvalidateQuery from "@/inertia/Modules/MastersCategories/Composables/UseInvalidateQuery.js";
import { parseApiError } from "@/inertia/Utils/parseApiError.js";

const props = defineProps({
    isUpdate: {
        type: Boolean,
        default: false,
    },
    category: {
        type: Object,
        default: null,
    },
})
const toast = useToast();
const dialogRef = inject('dialogRef');

const {useCreateCategory, useUpdateCategory} = useMutation()
const {useInvalidateFetchCategoryPaginated} = useInvalidateQuery();
const {mutate: createCategoryMutation} = useCreateCategory({
        onSuccess:async () => {
            await useInvalidateFetchCategoryPaginated();
            toast.add({ severity: 'success', summary: 'Success', life: 2500 });
            dialogRef.value.close();
        },
        onError: showError,
    }
)

const {mutate: updateCategoryMutation} = useUpdateCategory({
        onSuccess:async () => {
            await useInvalidateFetchCategoryPaginated();
            toast.add({ severity: 'success', summary: 'Success', life: 2500 });
            dialogRef.value.close();
        },
        onError: showError,
    }
)

const serverErrors = ref({});
const showError = (error) => {
    const { message, detailText, fieldErrors } = parseApiError(error, 'Gagal menyimpan category');
    serverErrors.value = fieldErrors;
    toast.add({ severity: 'error', summary: message, detail: detailText, life: 4000 });
};

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
    serverErrors.value = {};
    const id = props?.category?.id;
    const payload = {
        name: values.name,
        description: values.description,
    }
    if(id) {
        updateCategoryMutation( {id:id, payload})
    } else {
        createCategoryMutation({payload})
    }

};


watch(
    () => props.category,
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
                        <Message
                            v-if="!$form.name?.invalid && serverErrors.name"
                            severity="error"
                            variant="simple"
                            size="small"
                        >
                            {{ serverErrors.name }}
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
                    <Message
                        v-if="!$form.description?.invalid && serverErrors.description"
                        severity="error"
                        size="small"
                        variant="simple"
                    >
                        {{ serverErrors.description }}
                    </Message>
            </div>
            <div class="flex justify-end">
                <Button type="submit" :label="`${props?.isUpdate ? 'Update' : 'Submit' }`" icon="pi pi-send" class="mt-4" />
            </div>
        </Form>
    </div>
</template>
