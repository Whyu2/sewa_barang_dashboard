<script setup>

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
        <div class="grid grid-cols-2 gap-6">
            <div>
                <p class="text-gray-500 text-sm">Foto Produk</p>
                <div class="flex flex-col items-start gap-2">
                    <Image :src="props.product.photo_url" class="w-50" />
                </div>
            </div>
            <div>
                <p class="text-gray-500 text-sm">Nama Produk</p>
                <p class="text-lg font-semibold">{{ props.product.name }}</p>
            </div>

            <div>
                <p class="text-gray-500 text-sm">Kategori</p>
                <p class="text-lg font-semibold">{{ props.product.category_name }}</p>
            </div>

            <div>
                <p class="text-gray-500 text-sm">Status</p>
                <div>
                    <Tag :value="props.product.status" />
                </div>
            </div>

            <div>
                <p class="text-gray-500 text-sm">QR Code</p>
                <div class="flex flex-col items-start gap-2">
                    <Image :src="props.product.qr_code_url" class="w-28 h-28" />
                    <Button label="Download QR" size="small" icon="pi pi-download" severity="secondary"
                        @click="downloadImage" />
                </div>
            </div>
            <div>
                <p class="text-gray-500 text-sm">Region - QTY</p>
                <div v-for="region in props.product.product_region">
                    <p>{{ region.region_name }} - {{ region.qty }}</p>
                </div>

            </div>

            <div class="col-span-2">
                <p class="text-gray-500 text-sm">Deskripsi</p>
                <p class="mt-2">{{ props.product.description ?? '-' }}</p>
            </div>

        </div>
    </Card>
</template>
