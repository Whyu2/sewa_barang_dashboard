<script setup>
import {inject, ref, watch} from 'vue';
import { useToast } from 'primevue/usetoast';
import { Form } from '@primevue/forms';
import { yupResolver } from '@primevue/forms/resolvers/yup';
import * as yup from "yup";
import useMutation from "@/inertia/Modules/MastersUsers/Composables/UseMutation.js";
import useInvalidateQuery from "@/inertia/Modules/MastersUsers/Composables/UseInvalidateQuery.js";
import useQueryRegions from "@/inertia/Modules/MastersRegions/Composables/UseQuery.js";
import { parseApiError } from "@/inertia/Utils/parseApiError.js";
import { DEFAULT_USER_ROLE, USER_ROLE_OPTIONS, USER_ROLE_VALUES } from "@/inertia/Enums/UserRole.js";

const props = defineProps({
    isUpdate: {
        type: Boolean,
        default: false,
    },
    user: {
        type: Object,
        default: null,
    },
})
const toast = useToast();
const dialogRef = inject('dialogRef');

const {useCreateUser, useUpdateUser} = useMutation()
const {useInvalidateFetchUserPaginated} = useInvalidateQuery();
const {useFetchRegionPaginated} = useQueryRegions();
const {data: regionOpts} = useFetchRegionPaginated();

const showError = (error) => {
    const { message, detailText, fieldErrors } = parseApiError(error, 'Gagal menyimpan pengguna');
    serverErrors.value = fieldErrors;
    toast.add({ severity: 'error', summary: message, detail: detailText, life: 4000 });
};

const serverErrors = ref({});

const {mutate: createUserMutation} = useCreateUser({
        onSuccess:async () => {
            await useInvalidateFetchUserPaginated();
            toast.add({ severity: 'success', summary: 'Data berhasil disimpan', life: 2500 });
            dialogRef.value.close();
        },
        onError: showError,
    }
)

const {mutate: updateUserMutation} = useUpdateUser({
        onSuccess:async () => {
            await useInvalidateFetchUserPaginated();
            toast.add({ severity: 'success', summary: 'Data berhasil diperbarui', life: 2500 });
            dialogRef.value.close();
        },
        onError: showError,
    }
)

const roleOpts = USER_ROLE_OPTIONS;

const initialValues = ref({
    name: '',
    email: '',
    password: '',
    role: DEFAULT_USER_ROLE,
    region_id: null,
});

const resolver = yupResolver(
    yup.object({
        name: yup.string().required('Nama wajib diisi'),
        email: yup.string().email('Email tidak valid').required('Email wajib diisi'),
        password: yup.string().when([], {
            is: () => !props.isUpdate,
            then: (schema) => schema.required('Kata sandi wajib diisi').min(6, 'Minimal 6 karakter'),
            otherwise: (schema) => schema.transform(v => v === '' ? undefined : v).nullable().notRequired().min(6, 'Minimal 6 karakter'),
        }),
        role: yup.string().oneOf(USER_ROLE_VALUES).required('Peran wajib dipilih'),
        region_id: yup.number().typeError('Wilayah wajib dipilih').required('Wilayah wajib dipilih'),
    })
);

const onFormSubmit = ({ valid, values }) => {
    if (!valid) {
        return;
    }
    serverErrors.value = {};
    const id = props?.user?.id;
    const payload = {
        name: values.name,
        email: values.email,
        // Role selalu terkunci: create default staff, edit pertahankan role yang tampil.
        role: id ? (props.user?.role ?? DEFAULT_USER_ROLE) : DEFAULT_USER_ROLE,
        region_id: values.region_id,
    };
    // Kata sandi opsional saat edit: kosong = tidak diubah (BE unset password kosong)
    if (values.password) {
        payload.password = values.password;
    } else if (!props.isUpdate) {
        payload.password = values.password;
    }
    if(id) {
        updateUserMutation({id:id, payload})
    } else {
        createUserMutation({payload})
    }
};


watch(
    () => props.user,
    newValue => {
        if (!newValue) {
            return;
        }
        initialValues.value = {
            name: newValue.name ?? '',
            email: newValue.email ?? '',
            password: '',
            role: newValue.role ?? DEFAULT_USER_ROLE,
            region_id: newValue.region_id ?? null,
        };
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
                <label for="name">Nama</label>
                    <InputText name="name" placeholder="cth: Budi Santoso" class="w-full" />
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
                <label for="email">Email</label>
                <InputText name="email" type="email" placeholder="cth: budi@example.com" class="w-full" />
                <Message
                    v-if="$form.email?.invalid"
                    severity="error"
                    size="small"
                    variant="simple"
                >
                        {{ $form.email.error?.message }}
                    </Message>
                    <Message
                        v-if="!$form.email?.invalid && serverErrors.email"
                        severity="error"
                        size="small"
                        variant="simple"
                    >
                        {{ serverErrors.email }}
                    </Message>
            </div>

            <div class="mb-2">
                <label for="password" class="block mb-1">Kata Sandi {{ props?.isUpdate ? '(kosongkan jika tidak diubah)' : '' }}</label>
                <Password name="password" placeholder="Minimal 6 karakter" class="w-full" inputClass="w-full" toggleMask :feedback="false" />
                <Message
                    v-if="$form.password?.invalid"
                    severity="error"
                    size="small"
                    variant="simple"
                >
                        {{ $form.password.error?.message }}
                    </Message>
                    <Message
                        v-if="!$form.password?.invalid && serverErrors.password"
                        severity="error"
                        size="small"
                        variant="simple"
                    >
                        {{ serverErrors.password }}
                    </Message>
            </div>

            <div class="mb-2">
                <label for="role">Peran</label>
                <Dropdown name="role" :options="roleOpts" optionLabel="label" optionValue="value"
                    placeholder="Pilih Peran" checkmark :highlightOnSelect="false" class="w-full" disabled />
                <Message
                    v-if="$form.role?.invalid"
                    severity="error"
                    size="small"
                    variant="simple"
                >
                        {{ $form.role.error?.message }}
                    </Message>
                    <Message
                        v-if="!$form.role?.invalid && serverErrors.role"
                        severity="error"
                        size="small"
                        variant="simple"
                    >
                        {{ serverErrors.role }}
                    </Message>
            </div>

            <div class="mb-2">
                <label for="region_id">Wilayah</label>
                <Dropdown name="region_id" :options="regionOpts?.data || []" optionLabel="name" optionValue="id"
                    placeholder="Pilih Wilayah" checkmark :highlightOnSelect="false" class="w-full" />
                <Message
                    v-if="$form.region_id?.invalid"
                    severity="error"
                    size="small"
                    variant="simple"
                >
                        {{ $form.region_id.error?.message }}
                    </Message>
                    <Message
                        v-if="!$form.region_id?.invalid && serverErrors.region_id"
                        severity="error"
                        size="small"
                        variant="simple"
                    >
                        {{ serverErrors.region_id }}
                    </Message>
            </div>
            <div class="flex justify-end">
                <Button type="submit" :label="`${props?.isUpdate ? 'Perbarui' : 'Simpan' }`" icon="pi pi-check" class="mt-4" />
            </div>
        </Form>
    </div>
</template>
