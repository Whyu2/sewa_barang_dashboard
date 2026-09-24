<script setup>
const props = defineProps({
    title: { type: String, required: true },
    value: { type: Number, required: true },
    icon: { type: String, required: false },
});
</script>

<template>
    <div class="p-4 bg-surface-0 dark:bg-surface-900 border border-surface-200 dark:border-surface-800 shadow rounded flex items-center gap-4">
        <i :class="`${props.icon} text-3xl text-blue-500`"></i>
        <div>
            <p class="text-sm text-surface-500 dark:text-surface-400">{{ props.title }}</p>
            <p class="text-xl font-bold text-surface-900 dark:text-surface-0">{{ props.value }}</p>
        </div>
    </div>
</template>
