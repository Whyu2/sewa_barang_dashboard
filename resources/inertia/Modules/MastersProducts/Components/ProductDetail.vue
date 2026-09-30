<script setup>
import { computed } from 'vue';
import { getStatusSeverity, getStatusLabel } from '@/inertia/Utils/statusBadge.js';

const props = defineProps({
    product: {
        type: Object,
        default: null,
    },
});

const totalStock = computed(() => (props.product?.product_region || []).reduce((s, r) => s + (Number(r.qty) || 0), 0));
const regionCount = computed(() => (props.product?.product_region || []).length);

const downloadImage = async () => {
    try {
        const url = props.product.qr_code_url;
        if (!url) return;
        const slug = (props.product.name || 'produk').toLowerCase().replace(/[^a-z0-9]+/g, '-');
        const response = await fetch(url);
        const blob = await response.blob();
        const blobUrl = URL.createObjectURL(blob);
        const a = document.createElement("a");
        a.href = blobUrl;
        a.download = `qr-${slug}.png`;
        a.click();
        URL.revokeObjectURL(blobUrl);
    } catch (e) { /* biarkan toast global / abaikan */ }
};
</script>

<template>
    <div class="flex flex-col gap-4">
        <!-- Header ala referensi: thumbnail + judul + chip -->
        <div class="flex items-start gap-4">
            <div class="w-16 h-16 shrink-0 rounded-2xl overflow-hidden border border-surface-200 dark:border-surface-700 bg-surface-100 dark:bg-surface-800 flex items-center justify-center">
                <Image v-if="props.product.photo_url" :src="props.product.photo_url" preview imageClass="w-16 h-16 object-cover" />
                <i v-else class="pi pi-image text-2xl text-surface-400"></i>
            </div>
            <div class="flex-1 min-w-0">
                <p class="text-lg font-semibold text-surface-900 dark:text-surface-0 truncate">{{ props.product.name }}</p>
                <p class="text-xs text-surface-500">{{ props.product.category_name ?? '-' }} &middot; {{ regionCount }} wilayah &middot; {{ totalStock }} stok</p>
                <div class="mt-1"><Tag :value="getStatusLabel(props.product.status) ?? props.product.status" :severity="getStatusSeverity(props.product.status)" /></div>
            </div>
        </div>

        <!-- Frame isi -->
        <div class="rounded-2xl border border-surface-200 dark:border-surface-700 p-4 flex flex-col gap-4">
            <div class="rounded-xl overflow-hidden border border-surface-200 dark:border-surface-700">
                <Image v-if="props.product.photo_url" :src="props.product.photo_url" preview imageClass="w-full aspect-[16/9] object-cover" class="w-full" />
                <div v-else class="w-full aspect-[16/9] bg-surface-100 dark:bg-surface-800 flex items-center justify-center text-sm text-surface-400">Tidak ada foto</div>
            </div>

            <div class="grid grid-cols-2 gap-3">
                <div>
                    <p class="text-xs text-surface-500">Kategori</p>
                    <p class="font-medium mt-0.5">{{ props.product.category_name ?? '-' }}</p>
                </div>
                <div>
                    <p class="text-xs text-surface-500">Total Stok</p>
                    <p class="font-medium mt-0.5">{{ totalStock }}</p>
                </div>
            </div>

            <div>
                <p class="text-xs text-surface-500">Deskripsi</p>
                <p class="font-medium mt-0.5 whitespace-pre-wrap break-words text-sm">{{ props.product.description ?? '-' }}</p>
            </div>

            <div class="grid grid-cols-1 sm:grid-cols-2 gap-3">
                <div class="rounded-xl border border-surface-200 dark:border-surface-700 p-3">
                    <p class="text-xs text-surface-500 mb-2">QR Code</p>
                    <Image v-if="props.product.qr_code_url" :src="props.product.qr_code_url" preview imageClass="w-full max-w-[160px] aspect-square object-contain rounded-lg border border-surface-200 dark:border-surface-700" />
                    <span v-else class="text-xs text-surface-400">Tidak ada QR</span>
                    <Button v-if="props.product.qr_code_url" label="Unduh QR" size="small" icon="pi pi-download" severity="secondary" outlined class="mt-2 w-full" @click="downloadImage" />
                </div>
                <div class="rounded-xl border border-surface-200 dark:border-surface-700 p-3">
                    <p class="text-xs text-surface-500 mb-2">Stok per Wilayah</p>
                    <div v-if="props.product.product_region?.length" class="flex flex-col gap-1.5">
                        <div v-for="region in props.product.product_region" :key="region.region_id" class="flex justify-between items-center text-sm border border-surface-200 dark:border-surface-700 rounded-lg px-2.5 py-1.5">
                            <span class="truncate">{{ region.region_name }}</span><Badge :value="region.qty" />
                        </div>
                    </div>
                    <span v-else class="text-xs text-surface-400">Belum ada stok wilayah</span>
                </div>
            </div>
        </div>
    </div>
</template>
