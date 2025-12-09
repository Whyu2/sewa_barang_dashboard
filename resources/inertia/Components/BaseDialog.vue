<script setup>
import { inject, computed } from 'vue';

const dialogRef = inject('dialogRef');

// Get dynamic component and props from dialog data
const component = computed(() => dialogRef.value.data?.component);
const componentProps = computed(() => dialogRef.value.data?.componentProps ?? {});

const emits = defineEmits(['close', 'submit', 'cancel', 'update']);

function handleClose() {
  dialogRef.value?.close?.();
}

const handleUpdate = args => {
  emits('update', args);
};

function onSubmit(data) {
    emits('submit', data);
}
const onCancel = args => {
  emits('cancel', args);
  dialogRef.value?.close?.({ action: 'cancel' });
};
</script>

<template>
  <component
    :is="component"
    v-bind="componentProps"
    @close="handleClose"
    @update="handleUpdate"
    @submit="onSubmit"
    @cancel="onCancel"
  />
</template>
