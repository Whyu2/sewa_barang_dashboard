<script setup>
import LoaderOverlay from "@/inertia/Components/LoaderOverlay.vue";
import DynamicDialog from 'primevue/dynamicdialog';
import Toast from 'primevue/toast';
import ConfirmDialog from 'primevue/confirmdialog';

</script>
<template>
    <Toast />
    <DynamicDialog />
    <LoaderOverlay />
    <ConfirmDialog />
    <div>
        <slot />
    </div>
</template>
