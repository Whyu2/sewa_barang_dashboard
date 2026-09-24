<script setup>
import { inject, ref, watch } from 'vue';
import { useToast } from 'primevue/usetoast';
import { Form } from '@primevue/forms';
import { yupResolver } from '@primevue/forms/resolvers/yup';
import * as yup from 'yup';
import useMutation from '@/inertia/Modules/Transactions/Composables/UseMutation.js';
import useInvalidateQuery from '@/inertia/Modules/Transactions/Composables/UseInvalidateQuery.js';
import { isLate } from '@/inertia/Utils/isOverdue.js';
import { addDaysISO, diffDays } from '@/inertia/Utils/rentalDuration.js';
import { parseApiError } from '@/inertia/Utils/parseApiError.js';

const props = defineProps({ transaction: { type: Object, required: true } });
const toast = useToast();
const dialogRef = inject('dialogRef');
const { useUpdateTransaction } = useMutation();
const { useInvalidateFetchTransactionsPaginated } = useInvalidateQuery();

const { mutate: updateTx } = useUpdateTransaction({
    onSuccess: async () => {
        await useInvalidateFetchTransactionsPaginated();
        toast.add({ severity: 'success', summary: 'Updated', life: 2500 });
        dialogRef.value.close();
    },
    onError: (e) => {
        const { message, detailText } = parseApiError(e, 'Gagal menyimpan transaction');
        toast.add({ severity: 'error', summary: message, detail: detailText, life: 4000 });
    },
});

const initialValues = ref({
    renter_name: null,
    renter_phone: null,
    qty: 1,
    rent_price: 0,
    rent_date: null,
    rental_duration_days: 1,
    expected_return_date: null,
    return_date: null,
    status: null,
    notes: null,
});

const statusOptions = [
    { label: 'Disewa', value: 'rented' },
    { label: 'Dikembalikan', value: 'returned' },
];

const resolver = yupResolver(
    yup.object({
        renter_name: yup.string().required(),
        renter_phone: yup.string().required(),
        qty: yup.number().min(1).required(),
        rent_price: yup.number().min(0).required(),
        rent_date: yup.string().required(),
        rental_duration_days: yup.number().typeError('Durasi wajib diisi').min(1, 'Minimal 1 hari').required('Sewa berapa lama wajib diisi'),
        expected_return_date: yup.string().required(),
        status: yup.string().oneOf(['rented','returned','overdue']).required(),
        notes: yup.string().nullable(),
        return_date: yup.string().when('status', { is: 'returned', then: (s) => s.required('Return date wajib saat returned'), otherwise: (s) => s.nullable() }),
    })
);

const fileReturn = ref(null);
const previewReturn = ref(null);
const removeReturnProof = ref(false);

watch(() => props.transaction, (t) => {
    if (!t) return;
    const rent = t.rent_date ? t.rent_date.substring(0,10) : null;
    const expected = t.expected_return_date ? t.expected_return_date.substring(0,10) : null;
    const n = (rent && expected) ? diffDays(rent, expected) : null;
    initialValues.value = {
        renter_name: t.renter_name ?? null,
        renter_phone: t.renter_phone ?? null,
        qty: t.qty ?? 1,
        rent_price: t.rent_price ?? 0,
        rent_date: rent,
        rental_duration_days: (n !== null && n >= 1) ? n : 1,
        expected_return_date: expected,
        return_date: t.return_date ? t.return_date.substring(0,10) : null,
        status: t.status ?? 'rented',
        notes: t.notes ?? null,
    };
    previewReturn.value = t.return_proof_url ?? null;
    fileReturn.value = null;
    removeReturnProof.value = false;
}, { immediate: true });

// Hitung ulang expected_return_date dari rent_date + durasi (hari)
function recalcExpected(form) {
    if (!form) return;
    const rent = form.rent_date?.value;
    const days = Number(form.rental_duration_days?.value);
    if (!rent || !days || days < 1) return;
    form.expected_return_date.value = addDaysISO(rent, days);
}
function onFileSelectReturn(e) {
    const f = e.files[0];
    if (!f) return;
    fileReturn.value = f;
    removeReturnProof.value = false;
    const reader = new FileReader();
    reader.onload = (ev) => previewReturn.value = ev.target.result;
    reader.readAsDataURL(f);
}
// X pada foto baru = batalkan pilihan; X pada foto lama = tandai untuk dihapus permanen
function clearReturn() {
    if (fileReturn.value) {
        fileReturn.value = null;
        previewReturn.value = props.transaction.return_proof_url ?? null;
        removeReturnProof.value = false;
    } else {
        previewReturn.value = null;
        removeReturnProof.value = true;
    }
}
function undoRemoveReturn() { removeReturnProof.value = false; previewReturn.value = props.transaction.return_proof_url ?? null; }

const onSubmit = ({ valid, values }) => {
    if (!valid) return;
    // Pastikan expected selalu hasil kalkulasi (field disabled tidak bisa diubah manual)
    const autoExpected = (values.rent_date && Number(values.rental_duration_days) >= 1)
        ? addDaysISO(values.rent_date, Number(values.rental_duration_days))
        : values.expected_return_date;
    values = { ...values, expected_return_date: autoExpected };
    let submitStatus = values.status;
    if (values.status === 'returned' && isLate(values.return_date, values.expected_return_date)) {
        submitStatus = 'overdue';
    }
    const isReturnedLike = submitStatus === 'returned' || submitStatus === 'overdue';
    const wantRemoveProof = removeReturnProof.value && !fileReturn.value;
    if (fileReturn.value) {
        const fd = new FormData();
        Object.entries(values).forEach(([k, v]) => {
            if (v === null || v === undefined) return;
            if (k === 'return_date' && !isReturnedLike) return;
            if (k === 'status') { fd.append(k, submitStatus); return; }
            fd.append(k, v);
        });
        fd.set('status', submitStatus);
        if (isReturnedLike && !values.return_date) fd.set('return_date', values.return_date ?? '');
        fd.append('return_proof', fileReturn.value);
        updateTx({ id: props.transaction.id, payload: fd });
    } else {
        const payload = { ...values, status: submitStatus };
        if (wantRemoveProof) payload.remove_return_proof = true;
        if (!isReturnedLike) delete payload.return_date;
        if (payload.return_date === '' || payload.return_date === undefined) payload.return_date = null;
        if (payload.notes === '') payload.notes = null;
        updateTx({ id: props.transaction.id, payload });
    }
};
</script>
<template>
    <div class="card">
        <Form v-slot="$form" :key="transaction.id" :initialValues="initialValues" :resolver="resolver" @submit="onSubmit">
            <div class="mb-2">
                <label>Product</label>
                <InputText :modelValue="transaction.product?.name" disabled class="w-full" />
            </div>
            <div class="mb-2">
                <label>Region</label>
                <InputText :modelValue="transaction.region?.name" disabled class="w-full" />
            </div>
            <div class="mb-2">
                <label>Renter Name</label>
                <InputText name="renter_name" class="w-full" />
                <Message v-if="$form.renter_name?.invalid" severity="error" size="small" variant="simple">{{ $form.renter_name.error?.message }}</Message>
            </div>
            <div class="mb-2">
                <label>Renter Phone</label>
                <InputText name="renter_phone" class="w-full" />
                <Message v-if="$form.renter_phone?.invalid" severity="error" size="small" variant="simple">{{ $form.renter_phone.error?.message }}</Message>
            </div>
            <div class="grid grid-cols-2 gap-2">
                <div class="mb-2">
                    <label>Qty</label>
                    <InputNumber name="qty" class="w-full" :min="1" showButtons />
                    <Message v-if="$form.qty?.invalid" severity="error" size="small" variant="simple">{{ $form.qty.error?.message }}</Message>
                </div>
                <div class="mb-2">
                    <label>Rent Price</label>
                    <InputNumber name="rent_price" class="w-full" :min="0" mode="currency" currency="IDR" locale="id-ID" />
                    <Message v-if="$form.rent_price?.invalid" severity="error" size="small" variant="simple">{{ $form.rent_price.error?.message }}</Message>
                </div>
            </div>
            <div class="mb-2">
                <label>Rent Date</label>
                <InputText name="rent_date" type="date" class="w-full" @change="() => recalcExpected($form)" />
                <Message v-if="$form.rent_date?.invalid" severity="error" size="small" variant="simple">{{ $form.rent_date.error?.message }}</Message>
            </div>
            <div class="mb-2">
                <label>Sewa Berapa Lama? (hari)</label>
                <InputNumber name="rental_duration_days" class="w-full" :min="1" showButtons suffix=" hari" @update:modelValue="() => recalcExpected($form)" />
                <Message v-if="$form.rental_duration_days?.invalid" severity="error" size="small" variant="simple">{{ $form.rental_duration_days.error?.message }}</Message>
            </div>
            <div class="mb-2">
                <label>Expected Return Date</label>
                <InputText name="expected_return_date" type="date" class="w-full opacity-50" disabled />
                <small class="text-gray-400">Otomatis: rent date + lama sewa</small>
                <Message v-if="$form.expected_return_date?.invalid" severity="error" size="small" variant="simple">{{ $form.expected_return_date.error?.message }}</Message>
            </div>
            <div class="mb-2">
                <label>Status</label>
                <Dropdown name="status" :options="statusOptions" optionLabel="label" optionValue="value" placeholder="Select Status" checkmark :highlightOnSelect="false" class="w-full" />
                <Message v-if="$form.status?.invalid" severity="error" size="small" variant="simple">{{ $form.status.error?.message }}</Message>
            </div>
            <div class="mb-2">
                <label>Return Date <span v-if="$form.status?.value === 'returned'" class="text-red-500">*</span></label>
                <InputText name="return_date" type="date" class="w-full" :disabled="$form.status?.value !== 'returned'" :class="{ 'opacity-50': $form.status?.value !== 'returned' }" />
                <Message v-if="$form.return_date?.invalid" severity="error" size="small" variant="simple">{{ $form.return_date.error?.message }}</Message>
                <small v-if="$form.status?.value !== 'returned'" class="text-gray-400">Pilih status returned untuk mengisi tanggal pengembalian</small>
                <small v-if="$form.status?.value === 'returned' && $form.return_date?.value && $form.expected_return_date?.value && isLate($form.return_date.value, $form.expected_return_date.value)" class="text-orange-500">Terlambat >1 jam → status akan tersimpan sebagai overdue</small>
                <small v-else-if="$form.status?.value === 'returned' && $form.return_date?.value" class="text-green-600">On-time (≤ 1 jam) → returned</small>
            </div>
            <div v-if="$form.status?.value === 'returned'" class="mb-2">
                <label class="block mb-1">Return Proof (opsional)</label>
                <div v-if="previewReturn" class="flex items-center gap-2 mb-2">
                    <Image :src="previewReturn" alt="Return Proof" preview imageClass="w-24 h-24 object-cover rounded-lg border border-gray-300" />
                    <Button @click="clearReturn" icon="pi pi-times" rounded variant="outlined" severity="danger" size="small" v-tooltip.top="'Hapus foto'" />
                </div>
                <div v-else-if="removeReturnProof" class="flex items-center gap-2 mb-2">
                    <small class="text-red-500">Foto akan dihapus saat Update.</small>
                    <Button @click="undoRemoveReturn" label="Batalkan" size="small" severity="secondary" variant="outlined" />
                </div>
                <FileUpload mode="basic" @select="onFileSelectReturn" customUpload auto severity="secondary" accept="image/*" class="p-button-outlined" chooseLabel="Pilih Foto" />
            </div>
            <div class="mb-2">
                <label>Notes</label>
                <InputText name="notes" placeholder="Catatan transaksi" class="w-full" />
                <Message v-if="$form.notes?.invalid" severity="error" size="small" variant="simple">{{ $form.notes.error?.message }}</Message>
            </div>
            <div v-if="transaction.pickup_proof_url" class="mt-3">
                <label class="block mb-1 font-semibold text-sm">Pickup Proof (read-only)</label>
                <Image :src="transaction.pickup_proof_url" alt="Pickup Proof" preview imageClass="w-24 h-24 object-cover rounded-lg border border-gray-300" />
            </div>
            <div class="flex justify-end">
                <Button type="submit" label="Update" icon="pi pi-send" class="mt-4" />
            </div>
        </Form>
    </div>
</template>
