    <script setup>
    import { computed, onUnmounted, ref, watch } from "vue";
    import AppBreadcrumb from "@/inertia/Components/AppBreadcrumb.vue";
    import {Link, router} from '@inertiajs/vue3';
    import confirmDialog from "@/inertia/Composables/ConfirmDialog.js";
    import InertiaApp from "@/inertia/inertiaApp.vue";
    import useMutation from "@/inertia/Modules/Auth/Composables/UseMutation.js";
    import useAuthQuery from "@/inertia/Modules/Auth/Composables/useQuery.js";
    import useAuthStore from "@/inertia/Modules/Auth/Stores/useAuthStore.js";
    import {useToast} from "primevue/usetoast";
    import ProgressSpinner from 'primevue/progressspinner';

    const toast = useToast();
    const {baseConfirmDialog} = confirmDialog();
    const {useLogout} = useMutation();
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

    const {mutate: logout} = useLogout({
            onSuccess:async () => {
                authStore.logout();
                router.visit('/login')
                toast.add({ severity: 'success', summary: 'Success', life: 2500 });
            }
        }
    )

    const confirmLogout = () => {
        baseConfirmDialog({
            message: 'Do you want to logout ? ',
            header: 'Logout Confirmation',
            acceptLabel: 'Logout',
            onAccept: () =>  {
                logout()
            },
        });
    }

    const props = defineProps({
        useBreadcrumb: { type: Boolean, required: false, default: false },
    });

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
                    icon: 'pi pi pi-box',
                    route: '/master-product'
                },
            ]
        },
        {
            label: 'Note Log',
            icon: 'pi pi-file',
            route: '/log'
        },
    ]);
    </script>
    <template>
        <inertiaApp>
        <div v-if="checking" class="flex flex-col items-center justify-center gap-3" style="min-height: 60vh">
            <ProgressSpinner style="width:50px;height:50px" />
            <p class="text-sm text-gray-500">Memverifikasi sesi...</p>
        </div>
        <template v-else>
        <div class="card">
                <Menubar :model="items">
                    <template #item="{ item, props, hasSubmenu, root }">
                        <Link
                            v-if="item.route"
                            :href="item.route"
                            v-bind="props.action"
                            class="flex items-center gap-2"
                        >
                            <i :class="item.icon"></i>
                            <span>{{ item.label }}</span>
                        </Link>
                        <a v-else class="flex items-center" v-bind="props.action">
                        <i :class="item.icon"></i>
                            <span>{{ item.label }}</span>
                        </a>
                    </template>
                    <template #end>
                        <Button
                            icon="pi pi-sign-out"
                            class="flex items-center gap-2"
                            @click="confirmLogout"
                        >
                        </Button>

                    </template>
                </Menubar>
            </div>

                <AppBreadcrumb v-if="props.useBreadcrumb"/>

            <div class="pl-4 pr-4 pt-4">

                <slot />
            </div>
        </template>
        </inertiaApp>

    </template>
