<script setup>

import DashboardLayout from "@/inertia/Modules/Dashboard/Layouts/DashboardLayout.vue";
import StatsCard from "@/inertia/Modules/Dashboard/Components/StatsCard.vue";
</script>

<template>
    <DashboardLayout>
        <div class="grid grid-cols-4 gap-4 mb-6">
            <StatsCard title="Total Barang" value="128" icon="pi pi-box" />
            <StatsCard title="Sedang Dipinjam" value="24" icon="pi pi-shopping-cart" />
            <StatsCard title="Tersedia" value="90" icon="pi pi-check-circle" />
            <StatsCard title="Barang Rusak" value="14" icon="pi pi-exclamation-triangle" />
        </div>
    </DashboardLayout>
</template>
