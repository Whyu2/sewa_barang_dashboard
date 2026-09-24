<script setup>
import { computed, onUnmounted, ref, watch } from "vue";
import AppBreadcrumb from "@/inertia/Components/AppBreadcrumb.vue";
import { Link, router, usePage } from '@inertiajs/vue3';
import confirmDialog from "@/inertia/Composables/ConfirmDialog.js";
import InertiaApp from "@/inertia/inertiaApp.vue";
import useMutation from "@/inertia/Modules/Auth/Composables/UseMutation.js";
import useAuthQuery from "@/inertia/Modules/Auth/Composables/useQuery.js";
import useAuthStore from "@/inertia/Modules/Auth/Stores/useAuthStore.js";
import { useToast } from "primevue/usetoast";
import ProgressSpinner from 'primevue/progressspinner';
import useTheme from "@/inertia/Composables/useTheme.js";

const { isDark, toggleTheme } = useTheme();

const toast = useToast();
const { baseConfirmDialog } = confirmDialog();
const { useLogout } = useMutation();
const authStore = useAuthStore();

// Verifikasi sesi ke server: selama belum ada data user yang valid,
// tampilkan loading netral agar konten dashboard tak sempat terlihat.
// (Hasil di-cache 5 menit, jadi tidak request ulang tiap pindah halaman.)
const { useFetchMe } = useAuthQuery();
const meQuery = useFetchMe();
const checking = computed(() => meQuery.isPending.value);
// Beri tahu LoaderOverlay global agar sembunyi selama gate aktif (single loader).
watch(checking, (v) => authStore.setGateChecking(v), { immediate: true });
onUnmounted(() => authStore.setGateChecking(false));
// Pengaman: bila verifikasi gagal dan token sudah dibersihkan interceptor 401,
// pastikan user dilempar ke /login meski interceptor terlewat.
watch(meQuery.isError, (failed) => {
    if (failed && !localStorage.getItem('access_token')) {
        router.visit('/login');
    }
});

const { mutate: logout } = useLogout({
    onSuccess: async () => {
        authStore.logout();
        router.visit('/login')
        toast.add({ severity: 'success', summary: 'Success', life: 2500 });
    }
})

const confirmLogout = () => {
    baseConfirmDialog({
        message: 'Do you want to logout ? ',
        header: 'Logout Confirmation',
        acceptLabel: 'Logout',
        onAccept: () => {
            logout()
        },
    });
}

const props = defineProps({
    useBreadcrumb: { type: Boolean, required: false, default: false },
});

// --- Drawer navigation state ---
const visible = ref(false);
const expandedMaster = ref(false);

const page = usePage();
const currentUrl = computed(() => page.url);

const isActive = (route) => {
    if (!route) return false;
    if (route === '/') return currentUrl.value === '/';
    return currentUrl.value.startsWith(route);
};

const isMasterActive = computed(() =>
    ['/master-category', '/master-region', '/master-product', '/master-user'].some((r) =>
        currentUrl.value.startsWith(r)
    )
);
// Buka otomatis grup Master bila posisi aktif ada di dalamnya
watch(isMasterActive, (v) => { if (v) expandedMaster.value = true; }, { immediate: true });

const items = ref([
    {
        label: 'Home',
        icon: 'pi pi-home',
        route: '/'
    },
    {
        label: 'Transaction',
        icon: 'pi pi-shopping-cart',
        route: '/transaction'
    },
    {
        label: 'Master',
        icon: 'pi pi-database',
        items: [
            {
                label: 'Category',
                icon: 'pi pi-tag',
                route: '/master-category'
            },
            {
                label: 'Region',
                icon: 'pi pi-map-marker',
                route: '/master-region'
            },
            {
                label: 'Product',
                icon: 'pi pi-box',
                route: '/master-product'
            },
            {
                label: 'User',
                icon: 'pi pi-users',
                route: '/master-user'
            },
        ]
    },
    {
        label: 'Log',
        icon: 'pi pi-list',
        route: '/log'
    },
]);

const closeDrawer = () => { visible.value = false; };
const toggleMaster = () => { expandedMaster.value = !expandedMaster.value; };

const currentYear = new Date().getFullYear();
</script>

<template>
    <inertiaApp>
        <div v-if="checking" class="flex flex-col items-center justify-center gap-3" style="min-height: 60vh">
            <ProgressSpinner style="width:50px;height:50px" />
            <p class="text-sm text-gray-500">Memverifikasi sesi...</p>
        </div>
        <template v-else>
            <!-- Topbar -->
            <div class="flex items-center gap-2 px-4 py-2 sticky top-0 z-40 border-b border-surface-200 dark:border-surface-800 bg-surface-0 dark:bg-surface-900">
                <Button
                    icon="pi pi-bars"
                    text
                    rounded
                    aria-label="Buka menu navigasi"
                    @click="visible = true"
                />
                <span class="flex-1"></span>
                    <Button
                        :icon="isDark ? 'pi pi-sun' : 'pi pi-moon'"
                        text
                        rounded
                        :aria-label="isDark ? 'Ubah ke mode terang' : 'Ubah ke mode gelap'"
                        @click="toggleTheme"
                    />
                    <Button
                        icon="pi pi-sign-out"
                        text
                        severity="success"
                        rounded
                        aria-label="Logout"
                        @click="confirmLogout"
                    />
            </div>

            <!-- Drawer navigasi (overlay) -->
            <Drawer v-model:visible="visible">
                <nav class="flex flex-col gap-1">
                    <template v-for="item in items" :key="item.label">
                        <!-- Item dengan route langsung -->
                        <Link
                            v-if="item.route"
                            :href="item.route"
                            @click="closeDrawer"
                            class="flex items-center gap-3 px-3 py-2 rounded-lg no-underline transition-colors"
                            :class="isActive(item.route)
                                ? 'bg-primary-100 text-primary-700 font-medium dark:bg-primary-900 dark:text-primary-100'
                                : 'text-surface-700 hover:bg-surface-100 dark:text-surface-200 dark:hover:bg-surface-800'"
                        >
                            <i :class="item.icon"></i>
                            <span>{{ item.label }}</span>
                        </Link>

                        <!-- Grup Master (expandable) -->
                        <div v-else>
                            <button
                                type="button"
                                @click="toggleMaster"
                                class="w-full flex items-center gap-3 px-3 py-2 rounded-lg transition-colors cursor-pointer"
                                :class="isMasterActive
                                    ? 'bg-primary-100 text-primary-700 font-medium dark:bg-primary-900 dark:text-primary-100'
                                    : 'text-surface-700 hover:bg-surface-100 dark:text-surface-200 dark:hover:bg-surface-800'"
                            >
                                <i :class="item.icon"></i>
                                <span class="flex-1 text-left">{{ item.label }}</span>
                                <i :class="expandedMaster ? 'pi pi-chevron-down' : 'pi pi-chevron-right'" class="text-xs"></i>
                            </button>
                            <div v-show="expandedMaster" class="flex flex-col gap-1 pl-8 mt-1">
                                <Link
                                    v-for="sub in item.items"
                                    :key="sub.label"
                                    :href="sub.route"
                                    @click="closeDrawer"
                                    class="flex items-center gap-3 px-3 py-2 rounded-lg no-underline transition-colors"
                                    :class="isActive(sub.route)
                                        ? 'bg-primary-100 text-primary-700 font-medium dark:bg-primary-900 dark:text-primary-100'
                                        : 'text-surface-700 hover:bg-surface-100 dark:text-surface-200 dark:hover:bg-surface-800'"
                                >
                                    <i :class="sub.icon"></i>
                                    <span>{{ sub.label }}</span>
                                </Link>
                            </div>
                        </div>
                    </template>
                </nav>

                <template #footer>
                    <div class="flex flex-col gap-2">
                        <Button
                            label="Logout"
                            icon="pi pi-sign-out"
                            severity="danger"
                            outlined
                            class="w-full"
                            @click="closeDrawer(); confirmLogout();"
                        />
                    </div>
                </template>
            </Drawer>

            <AppBreadcrumb v-if="props.useBreadcrumb" />

            <div class="pl-4 pr-4 pt-4 pb-4 min-h-[calc(100vh-220px)]">
                <slot />
            </div>

            <footer class="mt-4 px-4 py-3 border-t border-surface-200 dark:border-surface-800 bg-surface-0 dark:bg-surface-900">
                <p class="m-0 text-center text-sm text-surface-500 dark:text-surface-400">
                    © {{ currentYear }}.
                </p>
            </footer>
        </template>
    </inertiaApp>
</template>
