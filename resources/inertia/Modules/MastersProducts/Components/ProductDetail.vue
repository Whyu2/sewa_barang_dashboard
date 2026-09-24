<script setup>
import { getStatusSeverity } from '@/inertia/Utils/statusBadge.js';

const props = defineProps({
    product: {
        type: Object,
        default: null,
    },
})
const downloadImage = async () => {
    const url = props.product.qr_code_url;
    const fileName = `qr-${props.product.name}.png`;

    const response = await fetch(url);
    const blob = await response.blob();
    const blobUrl = URL.createObjectURL(blob);

    const a = document.createElement("a");
    a.href = blobUrl;
    a.download = fileName;
    a.click();

    URL.revokeObjectURL(blobUrl);
};

</script>

<template>
    <Card class="p-6">
        <div class="rounded-xl border border-gray-300 overflow-hidden mb-4">
            <Image v-if="props.product.photo_url" :src="props.product.photo_url" preview class="w-full max-h-64 object-cover" />
            <div v-else class="w-full h-32 bg-surface-0 dark:bg-surface-900 flex items-center justify-center">- tidak ada foto -</div>
            <div class="px-4 py-3 bg-surface-0 dark:bg-surface-900">
                <p class="font-semibold">{{ props.product.name }}</p>
                <p class="text-xs text-gray-500">{{ props.product.category_name ?? '-' }}</p>
            </div>
        </div>

        <div class="rounded-lg border border-gray-300 divide-y divide-gray-300 text-sm">
            <div class="grid grid-cols-2 divide-x divide-gray-300">
                <div class="p-3">
                    <p class="text-xs text-gray-500 uppercase tracking-wide">Kategori</p>
                    <p class="font-medium mt-1">{{ props.product.category_name ?? '-' }}</p>
                </div>
                <div class="p-3">
                    <p class="text-xs text-gray-500 uppercase tracking-wide">Status</p>
                    <div class="mt-1"><Tag :value="props.product.status" :severity="getStatusSeverity(props.product.status)" /></div>
                </div>
            </div>
            <div class="p-3">
                <p class="text-xs text-gray-500 uppercase tracking-wide">Deskripsi</p>
                <p class="font-medium mt-1 whitespace-pre-wrap break-words">{{ props.product.description ?? '-' }}</p>
            </div>
            <div class="grid grid-cols-2 gap-4 p-3">
                <div>
                    <p class="text-xs text-gray-500 uppercase tracking-wide mb-2">QR Code</p>
                    <Image :src="props.product.qr_code_url" class="w-28 h-28 border border-gray-300 rounded-lg" />
                    <Button label="Unduh QR" size="small" icon="pi pi-download" severity="secondary" class="mt-2" @click="downloadImage" />
                </div>
                <div>
                    <p class="text-xs text-gray-500 uppercase tracking-wide mb-2">Wilayah - Stok</p>
                    <div class="space-y-1">
                        <div v-for="region in props.product.product_region" :key="region.region_id" class="flex justify-between text-sm border border-gray-300 rounded px-2 py-1">
                            <span>{{ region.region_name }}</span><span class="font-medium">{{ region.qty }}</span>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </Card>
</template>
