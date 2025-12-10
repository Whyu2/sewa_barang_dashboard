<script setup>
import {inject, ref, onMounted, watch} from 'vue';
import { useToast } from 'primevue/usetoast';
import { Form } from '@primevue/forms';
import { yupResolver } from '@primevue/forms/resolvers/yup';
import * as yup from "yup";
import useMutation from "@/inertia/Modules/MastersProducts/Composables/UseMutation.js";
import useInvalidateQuery from "@/inertia/Modules/MastersProducts/Composables/UseInvalidateQuery.js";
import useQuery from "@/inertia/Modules/MastersProducts/Composables/UseQuery.js";

const props = defineProps({
    isUpdate: {
        type: Boolean,
        default: false,
    },
    product: {
        type: Object,
        default: null,
    },
})
const toast = useToast();
const dialogRef = inject('dialogRef');
const {useFetchRegions, useFetchCategories} = useQuery();
const { data:  regionsOpts } = useFetchRegions();
const { data:  categoryOpts } = useFetchCategories();
const {useCreateProduct, useUpdateProduct} = useMutation();
const {useInvalidateFetchProductPaginated} = useInvalidateQuery();


const {mutate: createProductMutation} = useCreateProduct({
        onSuccess:async () => {
            await useInvalidateFetchProductPaginated();
            toast.add({ severity: 'success', summary: 'Success', life: 2500 });
            dialogRef.value.close();
        }
    }
)

const {mutate: updateProductMutation} = useUpdateProduct({
        onSuccess:async () => {
            await useInvalidateFetchProductPaginated();
            toast.add({ severity: 'success', summary: 'Success', life: 2500 });
            dialogRef.value.close();
        }
    }
)

const initialValues = ref({
    name:null,
    description: null,
    category_id: null,
    region_id: null,
    qty: 0,
});

const resolver = yupResolver(
    yup.object({
        name: yup.string().required(),
        description: yup.string().nullable(),
        category_id: yup.number().required(),
        region_id: yup.number().required(),
        qty: yup.number().required(),
    })
);

const onFormSubmit = ({ valid, values }) => {
    console.log(values  );
    if (!valid) {
        return;
    }
    const id = props?.product?.id;
    const payload = {
        name: values.name,
        description: values.description,
        category_id: values.category_id,
        region_id: values.region_id,
        qty: values.qty,
    }
    if(id) {
        updateProductMutation( {id:id, payload})
    } else {
        createProductMutation({payload})
    }
};


watch(
    () => props.product,
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
                    <InputText name="name" placeholder="Name" class="w-full" />
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
                <label for="category">Category</label>
                <Dropdown  name="category_id"  :options="categoryOpts" optionLabel="name" optionValue="id" placeholder="Select Category" checkmark :highlightOnSelect="false" class="w-full md:w-14rem" />
                <Message
                    v-if="$form.category_id?.invalid"
                    severity="error"
                    size="small"
                    variant="simple"
                >
                    {{ $form.category_id.error?.message }}
                </Message>
            </div>
            <div class="mb-2">
                <label  for="description">Region</label>
                <Dropdown name="region_id" :options="regionsOpts" optionLabel="name" optionValue="id" placeholder="Select Region" checkmark :highlightOnSelect="false" class="w-full md:w-14rem" />
                <Message
                    v-if="$form.region_id?.invalid"
                    severity="error"
                    size="small"
                    variant="simple"
                >
                    {{ $form.region_id.error?.message }}
                </Message>
            </div>
            <div class="mb-2">
                <label for="qty">QTY</label>
                    <InputNumber name="qty" placeholder="QTY" class="w-full" />
                <Message
                    v-if="$form.qty?.invalid"
                    severity="error"
                    size="small"
                    variant="simple"
                >
                    {{ $form.qty.error?.message }}
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
