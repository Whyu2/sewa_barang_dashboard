<script setup>
const props = defineProps({
    title: { type: String, required: true },
    value: { type: Number, required: true },
    icon: { type: String, required: false },
});
</script>

<template>
    <div class="p-4 bg-white shadow rounded flex items-center gap-4">
        <i :class="`${props.icon} text-3xl text-blue-500`"></i>
        <div>
            <p class="text-sm text-gray-500">{{ props.title }}</p>
            <p class="text-xl font-bold">{{ props.value }}</p>
        </div>
    </div>
</template>
