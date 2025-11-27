<script setup>
defineProps({
    title: String,
    value: [String, Number],
    icon: String
})
</script>

<template>
    <div class="p-4 bg-white shadow rounded flex items-center gap-4">
        <i :class="`${icon} text-3xl text-blue-500`"></i>
        <div>
            <p class="text-sm text-gray-500">{{ title }}</p>
            <p class="text-xl font-bold">{{ value }}</p>
        </div>
    </div>
</template>
