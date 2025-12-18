    <script setup>
    import { ref } from "vue";
    import AppBreadcrumb from "@/inertia/Components/AppBreadcrumb.vue";
    import {Link, router} from '@inertiajs/vue3';
    import confirmDialog from "@/inertia/Composables/ConfirmDialog.js";
    import InertiaApp from "@/inertia/inertiaApp.vue";
    import useMutation from "@/inertia/Modules/Auth/Composables/UseMutation.js";
    import useAuthStore from "@/inertia/Modules/Auth/Stores/useAuthStore.js";
    import {useToast} from "primevue/usetoast";

    const toast = useToast();
    const {baseConfirmDialog} = confirmDialog();
    const {useLogout} = useMutation();
    const authStore = useAuthStore();

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
        </inertiaApp>

    </template>
