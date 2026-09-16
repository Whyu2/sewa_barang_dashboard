<script setup>
import { formatIDR } from '@/inertia/Utils/formatIDR.js';
import { formatDateID } from '@/inertia/Utils/formatDate.js';
import { getStatusSeverity, getStatusLabel } from '@/inertia/Utils/statusBadge.js';
import { formatTRX } from '@/inertia/Utils/formatTRX.js';
const props = defineProps({ transaction: { type: Object, required: true } });
</script>
<template>
    <div class="flex flex-col gap-4 text-sm">
        <div class="rounded-xl border border-gray-300 overflow-hidden">
            <img v-if="transaction.product?.photo_url" :src="transaction.product.photo_url" alt="Foto Barang" class="w-full max-h-64 object-cover border-b border-gray-300" />
            <div v-else class="w-full h-32 bg-gray-50 flex items-center justify-center text-gray-400">- tidak ada foto -</div>
            <div class="px-4 py-2 bg-gray-50 flex justify-between text-xs">
                <span class="font-medium">{{ transaction.product?.name ?? '-' }}</span>
                <span class="text-gray-500">{{ transaction.product?.category_name ?? '-' }}</span>
            </div>
        </div>

        <div class="rounded-lg border border-gray-300 divide-y divide-gray-300">
            <div class="p-3">
                <p class="text-xs text-gray-500 uppercase tracking-wide">ID Transaksi</p>
                <p class="font-mono text-xs font-medium mt-1">{{ formatTRX(transaction.id) }}</p>
            </div>
            <div class="grid grid-cols-2 divide-x divide-gray-300">
                <div class="p-3">
                    <p class="text-xs text-gray-500 uppercase tracking-wide">Penyewa</p>
                    <p class="font-medium mt-1">{{ transaction.renter_name }}</p>
                </div>
                <div class="p-3">
                    <p class="text-xs text-gray-500 uppercase tracking-wide">HP</p>
                    <p class="font-medium mt-1">{{ transaction.renter_phone }}</p>
                </div>
            </div>
            <div class="grid grid-cols-2 divide-x divide-gray-300">
                <div class="p-3">
                    <p class="text-xs text-gray-500 uppercase tracking-wide">Region</p>
                    <p class="font-medium mt-1">{{ transaction.region?.name ?? '-' }}</p>
                </div>
                <div class="p-3">
                    <p class="text-xs text-gray-500 uppercase tracking-wide">Qty</p>
                    <p class="font-medium mt-1">{{ transaction.qty }}</p>
                </div>
            </div>
            <div class="p-3">
                <p class="text-xs text-gray-500 uppercase tracking-wide">Harga</p>
                <p class="font-medium mt-1">{{ formatIDR(transaction.rent_price) }}</p>
            </div>
            <div class="grid grid-cols-2 divide-x divide-gray-300">
                <div class="p-3">
                    <p class="text-xs text-gray-500 uppercase tracking-wide">Tgl Sewa</p>
                    <p class="font-medium mt-1">{{ formatDateID(transaction.rent_date) }}</p>
                </div>
                <div class="p-3">
                    <p class="text-xs text-gray-500 uppercase tracking-wide">Tgl Kembali (Rencana)</p>
                    <p class="font-medium mt-1">{{ formatDateID(transaction.expected_return_date) }}</p>
                </div>
            </div>
            <div class="grid grid-cols-2 divide-x divide-gray-300">
                <div class="p-3">
                    <p class="text-xs text-gray-500 uppercase tracking-wide">Tgl Kembali (Aktual)</p>
                    <p class="font-medium mt-1">{{ formatDateID(transaction.return_date) }}</p>
                </div>
                <div class="p-3">
                    <p class="text-xs text-gray-500 uppercase tracking-wide">Status</p>
                    <div class="mt-1"><Tag :value="getStatusLabel(transaction.status)" :severity="getStatusSeverity(transaction.status)" /></div>
                </div>
            </div>
            <div class="p-3">
                <p class="text-xs text-gray-500 uppercase tracking-wide">Notes</p>
                <p class="font-medium mt-1 whitespace-pre-wrap break-words">{{ transaction.notes ?? '-' }}</p>
            </div>
        </div>

        <div class="grid grid-cols-2 gap-4">
            <div class="rounded-lg border border-gray-300 p-3">
                <p class="text-xs text-gray-500 uppercase tracking-wide mb-2">Pickup Proof</p>
                <img v-if="transaction.pickup_proof_url" :src="transaction.pickup_proof_url" alt="Pickup Proof" class="rounded-lg w-full max-h-64 object-cover border border-gray-300" />
                <span v-else class="text-gray-400 text-xs">- tidak ada -</span>
            </div>
            <div class="rounded-lg border border-gray-300 p-3">
                <p class="text-xs text-gray-500 uppercase tracking-wide mb-2">Return Proof</p>
                <img v-if="transaction.return_proof_url" :src="transaction.return_proof_url" alt="Return Proof" class="rounded-lg w-full max-h-64 object-cover border border-gray-300" />
                <span v-else class="text-gray-400 text-xs">- tidak ada -</span>
            </div>
        </div>
    </div>
</template>
