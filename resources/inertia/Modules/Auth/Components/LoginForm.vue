<script setup>
import { ref,} from 'vue';
import { useToast } from 'primevue/usetoast';
import { Form } from '@primevue/forms';
import { yupResolver } from '@primevue/forms/resolvers/yup';
import * as yup from "yup";
import useMutation from "@/inertia/Modules/Auth/Composables/UseMutation.js";
import InertiaApp from "@/inertia/inertiaApp.vue";
import useAuthStore from "@/inertia/Modules/Auth/Stores/useAuthStore.js";
import { router } from '@inertiajs/vue3'



const toast = useToast();
const authStore = useAuthStore();
const {useLogin} = useMutation()
const {mutate: login} = useLogin({
        onSuccess:async (res) => {
            authStore.setIsAuthenticated(res.token);
            router.visit('/')
            toast.add({ severity: 'success', summary: 'Success', life: 2500 });
        }
    }
)


const initialValues = ref({
    email: '',
    password: '',
});

const resolver = yupResolver(
    yup.object({
        email: yup.string().required(),
        password: yup.string().required(),
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
    <inertiaApp>
    <div class="card">
        <Form v-slot="$form" :initialValues="initialValues" :resolver="resolver" @submit="onFormSubmit">
            <div class="mb-2">
                <label for="email">Email</label>
                    <InputText name="email" placeholder="Email"     class="w-full" />
                    <Message
                        v-if="$form.email?.invalid"
                        severity="error"
                        variant="simple"
                        size="small"
                    >
                        {{ $form.email.error?.message }}
                    </Message>
            </div>

            <div class="mb-2">
                <label for="password">Password</label>
                <InputText name="password" placeholder="Password" class="w-full" />
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
                <Button type="submit" label="Submit" icon="pi pi-send" class="mt-4" />
            </div>
        </Form>
    </div>
    </inertiaApp>
</template>
