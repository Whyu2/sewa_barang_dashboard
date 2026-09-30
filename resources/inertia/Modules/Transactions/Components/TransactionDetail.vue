<script setup>
import { formatIDR } from '@/inertia/Utils/formatIDR.js';
import { formatDateID } from '@/inertia/Utils/formatDate.js';
import { getStatusSeverity, getStatusLabel } from '@/inertia/Utils/statusBadge.js';
import { formatTRX } from '@/inertia/Utils/formatTRX.js';
import { formatDurationDays } from '@/inertia/Utils/rentalDuration.js';
const props = defineProps({ transaction: { type: Object, required: true } });
</script>
<template>
    <div class="flex flex-col gap-4">
        <!-- Header ala referensi -->
        <div class="flex items-start gap-4">
            <div class="w-16 h-16 shrink-0 rounded-2xl overflow-hidden border border-surface-200 dark:border-surface-700 bg-surface-100 dark:bg-surface-800 flex items-center justify-center">
                <img v-if="transaction.product?.photo_url" :src="transaction.product.photo_url" alt="Foto Barang" class="w-16 h-16 object-cover" />
                <i v-else class="pi pi-box text-2xl text-surface-400"></i>
            </div>
            <div class="flex-1 min-w-0">
                <p class="text-lg font-semibold text-surface-900 dark:text-surface-0 truncate">{{ transaction.product?.name ?? '-' }}</p>
                <p class="text-xs text-surface-500 font-mono">{{ formatTRX(transaction.id) }} &middot; {{ transaction.region?.name ?? '-' }}</p>
                <div class="mt-1"><Tag :value="getStatusLabel(transaction.status)" :severity="getStatusSeverity(transaction.status)" /></div>
            </div>
        </div>

        <!-- Frame isi -->
        <div class="rounded-2xl border border-surface-200 dark:border-surface-700 p-4 flex flex-col gap-4 text-sm">
            <div class="grid grid-cols-2 gap-3">
                <div>
                    <p class="text-xs text-surface-500">Penyewa</p>
                    <p class="font-medium mt-0.5">{{ transaction.renter_name }}</p>
                </div>
                <div>
                    <p class="text-xs text-surface-500">No. HP</p>
                    <p class="font-medium mt-0.5">{{ transaction.renter_phone }}</p>
                </div>
            </div>
            <div class="grid grid-cols-3 gap-3">
                <div>
                    <p class="text-xs text-surface-500">Jumlah</p>
                    <p class="font-medium mt-0.5">{{ transaction.qty }}</p>
                </div>
                <div class="col-span-2">
                    <p class="text-xs text-surface-500">Harga</p>
                    <p class="font-medium mt-0.5">{{ formatIDR(transaction.rent_price) }}</p>
                </div>
            </div>
            <div class="grid grid-cols-2 gap-3">
                <div>
                    <p class="text-xs text-surface-500">Tanggal Sewa</p>
                    <p class="font-medium mt-0.5">{{ formatDateID(transaction.rent_date) }}</p>
                </div>
                <div>
                    <p class="text-xs text-surface-500">Kembali (Rencana)</p>
                    <p class="font-medium mt-0.5">{{ formatDateID(transaction.expected_return_date) }}</p>
                    <p class="text-xs text-surface-500 mt-0.5">{{ formatDurationDays(transaction.rent_date, transaction.expected_return_date) }}</p>
                </div>
            </div>
            <div class="grid grid-cols-2 gap-3">
                <div>
                    <p class="text-xs text-surface-500">Kembali (Aktual)</p>
                    <p class="font-medium mt-0.5">{{ formatDateID(transaction.return_date) }}</p>
                </div>
                <div>
                    <p class="text-xs text-surface-500">Dibuat Oleh</p>
                    <p class="font-medium mt-0.5">{{ transaction.creator?.name ?? '-' }}</p>
                </div>
            </div>
            <div>
                <p class="text-xs text-surface-500">Catatan</p>
                <p class="font-medium mt-0.5 whitespace-pre-wrap break-words">{{ transaction.notes ?? '-' }}</p>
            </div>
            <div class="grid grid-cols-2 gap-3">
                <div class="rounded-xl border border-surface-200 dark:border-surface-700 p-3">
                    <p class="text-xs text-surface-500 mb-2">Bukti Pengambilan</p>
                    <Image v-if="transaction.pickup_proof_url" :src="transaction.pickup_proof_url" alt="Bukti pengambilan" preview imageClass="w-full aspect-square object-cover rounded-lg border border-surface-200 dark:border-surface-700" />
                    <span v-else class="text-surface-400 text-xs">Tidak ada</span>
                </div>
                <div class="rounded-xl border border-surface-200 dark:border-surface-700 p-3">
                    <p class="text-xs text-surface-500 mb-2">Bukti Pengembalian</p>
                    <Image v-if="transaction.return_proof_url" :src="transaction.return_proof_url" alt="Bukti pengembalian" preview imageClass="w-full aspect-square object-cover rounded-lg border border-surface-200 dark:border-surface-700" />
                    <span v-else class="text-surface-400 text-xs">Tidak ada</span>
                </div>
            </div>
        </div>
    </div>
</template>
