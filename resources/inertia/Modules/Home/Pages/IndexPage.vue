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

const { useFetchDashboardStats, useFetchDashboardCharts, useFetchDashboardTables } = homeUseQuery();
const { data: stats } = useFetchDashboardStats();
const { data: charts } = useFetchDashboardCharts();
const { data: tables } = useFetchDashboardTables();

const topProductsData = computed(() => {
    const p = charts.value?.topProducts || [];
    return { labels: p.map(x=>x.product), datasets: [{ label: 'Jumlah Transaksi', data: p.map(x=>x.trx_count), backgroundColor: '#60a5fa' }] };
});
const barOptions = { responsive:true, indexAxis:'y', scales:{ x:{ beginAtZero:true, ticks:{ precision:0 } } }, plugins:{ legend:{ display:false } } };
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
            <div class="p-4 bg-white shadow rounded-lg border border-gray-100 flex items-center gap-4"><i class="pi pi-dollar text-3xl text-green-500"></i><div><p class="text-sm text-gray-500">Pendapatan Bulan Ini</p><p class="text-lg font-bold">{{ formatIDR(stats?.revenueThisMonth) }}</p></div></div>
            <StatsCard title="Total Transaksi" :value="stats?.totalTransactions ?? 0" icon="pi pi-list" />
            <StatsCard title="Total Barang" :value="stats?.totalProducts ?? 0" icon="pi pi-box" />
            <StatsCard title="Disewa" :value="stats?.rented ?? 0" icon="pi pi-shopping-cart" />
            <StatsCard title="Dikembalikan" :value="stats?.returned ?? 0" icon="pi pi-verified" />
            <StatsCard title="Pengembalian Telat" :value="stats?.overdue ?? 0" icon="pi pi-exclamation-triangle" />
        </div>

        <div class="grid grid-cols-1 lg:grid-cols-3 gap-4 mb-6">
            <div class="bg-white shadow rounded p-4"><p class="font-semibold mb-2">Top 5 Produk Terbanyak Disewa</p><Chart type="bar" :data="topProductsData" :options="barOptions" /><p v-if="!topProductsData.labels.length" class="text-sm text-gray-400 text-center mt-2">Tidak ada transaksi</p></div>
            <div class="bg-white shadow rounded p-4"><p class="font-semibold mb-2">Distribusi Status</p><Chart type="doughnut" :data="statusData" /></div>
            <div class="bg-white shadow rounded p-4"><p class="font-semibold mb-2">Revenue per Region</p><Chart type="bar" :data="regionData" /></div>
        </div>

        <div class="grid grid-cols-1 xl:grid-cols-2 gap-4">
            <div class="bg-white shadow rounded p-4">
                <p class="font-semibold mb-2">Transaksi Terbaru</p>
                <DataTable :value="tables?.recent || []" size="small"><Column header="TRX ID" style="width:6rem"><template #body="{data}"><span class="font-mono text-xs">{{ formatTRX(data.id) }}</span></template></Column><Column header="Produk"><template #body="{data}">{{ data.product?.name }}</template></Column><Column header="Penyewa" field="renter_name"/><Column header="Tgl"><template #body="{data}">{{ formatDateID(data.rent_date) }}</template></Column><Column header="Status"><template #body="{data}"><Tag :value="getStatusLabel(data.status)" :severity="getStatusSeverity(data.status)"/></template></Column></DataTable>
            </div>
            <div class="bg-white shadow rounded p-4">
                <p class="font-semibold mb-2">Pengembalian Telat ({{ tables?.overdue?.length || 0 }})</p>
                <DataTable :value="tables?.overdue || []" size="small"><Column header="TRX ID" style="width:6rem"><template #body="{data}"><span class="font-mono text-xs">{{ formatTRX(data.id) }}</span></template></Column><Column header="Produk"><template #body="{data}">{{ data.product?.name }}</template></Column><Column header="Jatuh Tempo"><template #body="{data}">{{ formatDateID(data.expected_return_date) }} ({{ Math.floor(data.days_overdue) }} hari)</template></Column><Column header="Status"><template #body="{data}"><Tag :value="getStatusLabel(data.status)" :severity="getStatusSeverity(data.status)"/></template></Column></DataTable>
            </div>
        </div>
    </DashboardLayout>
</template>
