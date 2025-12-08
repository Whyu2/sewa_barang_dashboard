<script setup>
import {inject, ref} from 'vue';
import { useToast } from 'primevue/usetoast';
import { Form } from '@primevue/forms';
import { yupResolver } from '@primevue/forms/resolvers/yup';
import * as yup from "yup";
import useMutation from "@/inertia/Modules/MastersCategories/Composables/UseMutation.js";
import useInvalidateQuery from "@/inertia/Modules/MastersCategories/Composables/UseInvalidateQuery.js";
const toast = useToast();
const dialogRef = inject('dialogRef');

const {useCreateCategory} = useMutation()
const {useInvalidateFetchCategoryPaginated} = useInvalidateQuery();
const {mutate: createCategoryMutation} = useCreateCategory({
        onSuccess:async () => {
            await useInvalidateFetchCategoryPaginated();
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
        description: yup.string().required(),
    })
);

const onFormSubmit = ({ valid, values }) => {
    if (!valid) {
        return;
    }
    const payload = {
        name: values.name,
        description: values.description,
    }
    createCategoryMutation({payload})
};



</script>

<template>
    <div class="card">
        <Form v-slot="$form" :initialValues="initialValues" :resolver="resolver" @submit="onFormSubmit">
            <div class="mb-2">
                <label for="name">Name</label>
                    <InputText name="name" placeholder="Username"     class="w-full" />
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
                    variant="simple"
                    size="small"
                >
                    {{ $form.description.error?.message }}
                </Message>
            </div>
            <div class="flex justify-end">
                <Button type="submit" label="Submit" icon="pi pi-send" class="mt-4" />
            </div>


        </Form>
    </div>
</template>
