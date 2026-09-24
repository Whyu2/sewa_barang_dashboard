<script setup>
import { ref,} from 'vue';
import { useToast } from 'primevue/usetoast';
import { Form } from '@primevue/forms';
import { yupResolver } from '@primevue/forms/resolvers/yup';
import * as yup from "yup";
import useMutation from "@/inertia/Modules/Auth/Composables/UseMutation.js";
import useAuthStore from "@/inertia/Modules/Auth/Stores/useAuthStore.js";
import { parseApiError } from "@/inertia/Utils/parseApiError.js";
import { router } from '@inertiajs/vue3'



const toast = useToast();
const authStore = useAuthStore();
const {useLogin} = useMutation()
const {mutate: login} = useLogin({
        onSuccess:async (res) => {
            authStore.setIsAuthenticated(res.token);
            router.visit('/')
            toast.add({ severity: 'success', summary: 'Login berhasil', life: 2500 });
        },
        onError: (error) => {
            const { message, detailText } = parseApiError(error, 'Login gagal');
            toast.add({ severity: 'error', summary: message, detail: detailText, life: 4000 });
        },
    }
)


const initialValues = ref({
    email: '',
    password: '',
});

const resolver = yupResolver(
    yup.object({
        email: yup.string().email('Email tidak valid').required('Email wajib diisi'),
        password: yup.string().required('Password wajib diisi'),
    })
);

const onFormSubmit = ({ valid, values }) => {
    if (!valid) {
        return;
    }
    const payload = {
        email: values.email,
        password: values.password,
    }
    login({payload})
};



</script>

<template>
    <div class="card w-full">
        <Form v-slot="$form" :initialValues="initialValues" :resolver="resolver" @submit="onFormSubmit">
            <div class="mb-3">
                <label for="email" class="block mb-1">Email</label>
                    <InputText name="email" type="email" placeholder="nama@example.com" class="w-full" />
                    <Message
                        v-if="$form.email?.invalid"
                        severity="error"
                        variant="simple"
                        size="small"
                    >
                        {{ $form.email.error?.message }}
                    </Message>
            </div>

            <div class="mb-3">
                <label for="password" class="block mb-1">Password</label>
                <Password name="password" placeholder="Password" class="w-full" inputClass="w-full" toggleMask :feedback="false" />
                <Message
                    v-if="$form.password?.invalid"
                    severity="error"
                    size="small"
                    variant="simple"
                >
                    {{ $form.password.error?.message }}
                </Message>
            </div>
            <div class="flex justify-end">
                <Button type="submit" label="Login" icon="pi pi-sign-in" class="mt-2 w-full" />
            </div>
        </Form>
    </div>
</template>
