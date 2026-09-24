<script setup>
import LoginForm from "@/inertia/Modules/Auth/Components/LoginForm.vue";
import InertiaApp from "@/inertia/inertiaApp.vue";

</script>

<template>
    <InertiaApp>
        <div class="min-h-screen flex items-center justify-center p-6">
            <div class="w-full max-w-md rounded-2xl border border-surface-200 dark:border-surface-800 bg-surface-0 dark:bg-surface-900 shadow-lg p-8">
                <div class="text-center mb-6">
                    <div class="inline-flex items-center justify-center w-12 h-12 rounded-xl bg-primary-100 dark:bg-primary-900 mb-3">
                        <i class="pi pi-box text-2xl text-primary-600 dark:text-primary-300"></i>
                    </div>
                    <h1 class="text-2xl font-bold m-0">Sewa Barang</h1>
                    <p class="text-sm text-gray-500 mt-1">Masuk untuk mengelola dashboard penyewaan.</p>
                </div>
                <LoginForm />
            </div>
        </div>
    </InertiaApp>

</template>
