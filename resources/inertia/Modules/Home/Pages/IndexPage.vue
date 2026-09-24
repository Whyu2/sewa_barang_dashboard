<script setup>
import DashboardLayout from "@/inertia/Components/DashboardLayout.vue";
import StatsCard from "@/inertia/Modules/Home/Components/StatsCard.vue";
import homeUseQuery from "@/inertia/Modules/Home/Composables/homeUseQuery.js";
import { computed } from "vue";
import { formatIDR } from "@/inertia/Utils/formatIDR.js";
import { formatDateID } from "@/inertia/Utils/formatDate.js";
import { getStatusSeverity, getStatusColor, getStatusLabel } from "@/inertia/Utils/statusBadge.js";
import { formatTRX } from "@/inertia/Utils/formatTRX.js";
import DataTable from 'primevue/datatable';
import Column from 'primevue/column';
import useTheme from "@/inertia/Composables/useTheme.js";

const { isDark } = useTheme();

// Chart.js me-render ke <canvas> dan tidak membaca CSS/Prime,
// jadi warna tick, grid, dan legend harus diganti manual per tema.
const tickColor = computed(() => (isDark.value ? '#fafafa' : '#6b7280'));
const gridColor = computed(() => (isDark.value ? 'rgba(255,255,255,0.18)' : '#e5e7eb'));

const barOptions = computed(() => ({
    responsive: true,
    animation: { duration: 1000 },
    indexAxis: 'y',
    scales: {
        x: { beginAtZero: true, ticks: { precision: 0, color: tickColor.value }, grid: { color: gridColor.value } },
        y: { ticks: { color: tickColor.value }, grid: { color: gridColor.value } },
    },
    plugins: { legend: { display: false } },
}));
const columnOptions = computed(() => ({
    responsive: true,
    animation: { duration: 1000 },
    scales: {
        x: { ticks: { color: tickColor.value }, grid: { color: gridColor.value } },
        y: { beginAtZero: true, ticks: { precision: 0, color: tickColor.value }, grid: { color: gridColor.value } },
    },
    plugins: { legend: { display: false } },
}));
const pieOptions = computed(() => ({
    responsive: true,
    animation: { duration: 1000 },
    plugins: { legend: { position: 'bottom', labels: { color: tickColor.value } } },
}));

const { useFetchDashboardStats, useFetchDashboardCharts, useFetchDashboardTables } = homeUseQuery();
const { data: stats } = useFetchDashboardStats();
const { data: charts } = useFetchDashboardCharts();
const { data: tables } = useFetchDashboardTables();

const topProductsData = computed(() => {
    const p = charts.value?.topProducts || [];
    return { labels: p.map(x=>x.product), datasets: [{ label: 'Jumlah Transaksi', data: p.map(x=>x.trx_count), backgroundColor: '#60a5fa' }] };
});
// Chart hanya di-mount setelah data tiba: mount = data final -> animasi awal
// Chart.js selalu jalan mulus sekali, tanpa re-init berulang dari data kosong.
const chartsReady = computed(() => !!charts.value && 'topProducts' in charts.value);
const statusData = computed(() => {
    const s = charts.value?.statusDist || [];
    return { labels: s.map(x=>getStatusLabel(x.status)), datasets: [{ data: s.map(x=>x.count), backgroundColor: s.map(x=>getStatusColor(x.status)) }] };
});
const regionData = computed(() => {
    const r = charts.value?.revenuePerRegion || [];
    return { labels: r.map(x=>x.region), datasets: [{ label: 'Revenue', data: r.map(x=>x.revenue), backgroundColor: '#a78bfa' }] };
});
</script>
<template>
    <DashboardLayout>

        <div class="grid grid-cols-2 md:grid-cols-3 gap-4 mb-6">
            <div class="p-4 bg-surface-0 dark:bg-surface-900 shadow rounded-lg border border-surface-200 dark:border-surface-800 flex items-center gap-4"><i class="pi pi-dollar text-3xl text-green-500"></i><div><p class="text-sm text-surface-500 dark:text-surface-400">Pendapatan Bulan Ini</p><p class="text-lg font-bold text-surface-900 dark:text-surface-0">{{ formatIDR(stats?.revenueThisMonth) }}</p></div></div>
            <StatsCard title="Total Transaksi" :value="stats?.totalTransactions ?? 0" icon="pi pi-list" />
            <StatsCard title="Total Barang" :value="stats?.totalProducts ?? 0" icon="pi pi-box" />
            <StatsCard title="Disewa" :value="stats?.rented ?? 0" icon="pi pi-shopping-cart" />
            <StatsCard title="Dikembalikan" :value="stats?.returned ?? 0" icon="pi pi-verified" />
            <StatsCard title="Pengembalian Telat" :value="stats?.overdue ?? 0" icon="pi pi-exclamation-triangle" />
        </div>

        <div v-if="chartsReady" class="grid grid-cols-1 lg:grid-cols-3 gap-4 mb-6">
            <div class="bg-surface-0 dark:bg-surface-900 border border-surface-200 dark:border-surface-800 shadow rounded p-4"><p class="font-semibold mb-2 text-surface-900 dark:text-surface-0">Top 5 Produk Terbanyak Disewa</p><Chart type="bar" :data="topProductsData" :options="barOptions" /><p v-if="!topProductsData.labels.length" class="text-sm text-gray-400 text-center mt-2">Tidak ada transaksi</p></div>
            <div class="bg-surface-0 dark:bg-surface-900 border border-surface-200 dark:border-surface-800 shadow rounded p-4"><p class="font-semibold mb-2 text-surface-900 dark:text-surface-0">Distribusi Status</p><Chart type="doughnut" :data="statusData" :options="pieOptions" /></div>
            <div class="bg-surface-0 dark:bg-surface-900 border border-surface-200 dark:border-surface-800 shadow rounded p-4"><p class="font-semibold mb-2 text-surface-900 dark:text-surface-0">Revenue per Region</p><Chart type="bar" :data="regionData" :options="columnOptions" /></div>
        </div>
        <div v-else class="grid grid-cols-1 lg:grid-cols-3 gap-4 mb-6">
            <div v-for="i in 3" :key="i" class="bg-surface-0 dark:bg-surface-900 border border-surface-200 dark:border-surface-800 shadow rounded p-4"><div class="h-48 rounded bg-surface-100 dark:bg-surface-800 animate-pulse" /></div>
        </div>

        <div class="grid grid-cols-1 xl:grid-cols-2 gap-4">
            <div class="bg-surface-0 dark:bg-surface-900 border border-surface-200 dark:border-surface-800 shadow rounded p-4">
                <p class="font-semibold mb-2 text-surface-900 dark:text-surface-0">Transaksi Terbaru</p>
                <DataTable :value="tables?.recent || []" size="small"><Column header="TRX ID" style="width:6rem"><template #body="{data}"><span class="font-mono text-xs">{{ formatTRX(data.id) }}</span></template></Column><Column header="Produk"><template #body="{data}">{{ data.product?.name }}</template></Column><Column header="Penyewa" field="renter_name"/><Column header="Tgl Sewa"><template #body="{data}">{{ formatDateID(data.rent_date) }}</template></Column><Column header="Status"><template #body="{data}"><Tag :value="getStatusLabel(data.status)" :severity="getStatusSeverity(data.status)"/></template></Column></DataTable>
            </div>
            <div class="bg-surface-0 dark:bg-surface-900 border border-surface-200 dark:border-surface-800 shadow rounded p-4">
                <p class="font-semibold mb-2 text-surface-900 dark:text-surface-0">Pengembalian Telat ({{ tables?.overdue?.length || 0 }})</p>
                <DataTable :value="tables?.overdue || []" size="small"><Column header="TRX ID" style="width:6rem"><template #body="{data}"><span class="font-mono text-xs">{{ formatTRX(data.id) }}</span></template></Column><Column header="Produk"><template #body="{data}">{{ data.product?.name }}</template></Column><Column header="Tgl Perkiraan Pengembalian"><template #body="{data}">{{ formatDateID(data.expected_return_date) }}</template></Column><Column header="Status"><template #body="{data}"><Tag :value="getStatusLabel(data.status)" :severity="getStatusSeverity(data.status)"/></template></Column></DataTable>
            </div>
        </div>
    </DashboardLayout>
</template>
