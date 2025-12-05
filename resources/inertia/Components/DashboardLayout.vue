    <script setup>
    import { ref } from "vue";
    import AppBreadcrumb from "@/inertia/Components/AppBreadcrumb.vue";
    import LoaderOverlay from "@/inertia/Components/LoaderOverlay.vue";
    import { Link } from '@inertiajs/vue3'

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
                    label: 'Product Category',
                    icon: 'pi pi-tag',
                    route: '/master-category'

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
        <LoaderOverlay />
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
                        <div class="flex items-center gap-2">
                        </div>
                    </template>
                </Menubar>
            </div>
            <div class="p-5 ">
                <AppBreadcrumb v-if="props.useBreadcrumb"/>
                <slot />
            </div>


    </template>
