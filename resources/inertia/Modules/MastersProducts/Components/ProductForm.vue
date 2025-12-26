<script setup>
import {inject, ref, watch} from 'vue';
import { useToast } from 'primevue/usetoast';
import { Form } from '@primevue/forms';
import { yupResolver } from '@primevue/forms/resolvers/yup';
import * as yup from "yup";
import useMutation from "@/inertia/Modules/MastersProducts/Composables/UseMutation.js";
import useInvalidateQuery from "@/inertia/Modules/MastersProducts/Composables/UseInvalidateQuery.js";
import useQueryCategories from "@/inertia/Modules/MastersCategories/Composables/UseQuery.js";
import useQueryRegions from "@/inertia/Modules/MastersRegions/Composables/UseQuery.js";


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
const { useFetchCategoryPaginated} = useQueryCategories();
const { useFetchRegionPaginated} = useQueryRegions();
const { data:  categoryOpts } = useFetchCategoryPaginated();
const { data:  regionRaw } = useFetchRegionPaginated();
const {useCreateProduct, useUpdateProduct} = useMutation();
const {useInvalidateFetchProductPaginated} = useInvalidateQuery();

var regionOpts = ref([
]);

const regionQty = ref({});
const getQty = (id) => regionQty.value[id] ?? 0;
const setQty = (id, qty) => regionQty.value[id] = qty;

watch(
  regionRaw,
  (newValue) => {
 if (!Array.isArray(newValue.data)) return;

    regionOpts.value = (newValue.data || []).map(r => ({
      ...r,
      qty: r.qty ?? 0,
    }));
  },
  { immediate: true }
);


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
   product_region_id: []
});

const resolver = yupResolver(
    yup.object({
        name: yup.string().required(),
        description: yup.string().nullable(),
        category_id: yup.number().required(),
        product_region_id: yup.array().min(1).required(),
    })
);

const onFormSubmit = ({ valid, values }) => {
    if (!valid) {
        return;
    }
    const id = props?.product?.id;
    const product_region = values.product_region_id.map((id) => ({region_id: id, qty: getQty(id)}));
    const payload = {
        name: values.name,
        description: values.description,
        category_id: values.category_id,
        product_region: product_region,
    }
    
    if(id) {
        updateProductMutation( {id:id, payload})
    } else {
        createProductMutation({payload})
    }
};


watch(
  () => props.product,
  (product) => {
    if (!product) return;

    // isi checkbox terpilih
    initialValues.value = {
      name: product.name ?? null,
      description: product.description ?? null,
      category_id: product.category_id ?? null,
      product_region_id: product.product_region?.map(pr => pr.region_id) ?? []
    };

    // isi QTY sesuai relasi
    regionQty.value = {};
    product.product_region?.forEach(pr => {
      regionQty.value[pr.region_id] = pr.qty ?? 0;
    });
  },
  { immediate: true }
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
                <Dropdown  name="category_id"  :options="categoryOpts.data || []" optionLabel="name" optionValue="id" placeholder="Select Category" checkmark :highlightOnSelect="false" class="w-full md:w-14rem" />
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
            <div class="mb-2">
            <label for="product_region_id" class="block mb-1">
             Mapping Product Region
            </label>
               <CheckboxGroup name="product_region_id" class="flex flex-col gap-1">
                <div
                    v-for="region in regionOpts"
                    :key="region.id"
                    class="grid grid-cols-[1fr_auto_auto_auto] items-center gap-3"
                >
                    <label>{{ region.name }}</label>

                    <Checkbox
                    :value="region.id"
                    size="small"
                    />
                    <span class="text-sm">QTY:</span>
                    <InputNumber
                    :modelValue="getQty(region.id)"
                    @update:modelValue="val => setQty(region.id, val)"
                    placeholder="QTY"
                    showButtons
                    :min="0"
                    size="small"
                    :disabled="!$form.product_region_id?.value?.includes(region.id)"
                    />
                </div>
                </CheckboxGroup>

            
            </div>
            <div class="flex justify-end">
                <Button type="submit" :label="`${props?.isUpdate ? 'Update' : 'Submit' }`" icon="pi pi-send" class="mt-4" />
            </div>
        </Form>
    </div>
</template>
