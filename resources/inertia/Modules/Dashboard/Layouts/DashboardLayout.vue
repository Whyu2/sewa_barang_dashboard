<script setup>
import { ref } from "vue";
const items = ref([
    {
        label: 'Home',
        icon: 'pi pi-home'
    },
    {
        label: 'Transaction',
        icon: 'pi pi-shopping-cart',

    },
    {
        label: 'Master',
        icon: 'pi pi-database',

        items: [
            {
                label: 'Product Category',
                icon: 'pi pi-tag',

            },
            {
                label: 'Product',
                icon: 'pi pi pi-box',

            },
        ]
    },
    {
        label: 'Note Log',
        icon: 'pi pi-file',
    },
]);
</script>


<template>
        <div class="card">
            <Menubar :model="items">
                <template #item="{ item, props, hasSubmenu, root }">
                    <a v-ripple class="flex items-center" v-bind="props.action">
                        <i :class="item.icon"></i>
                        <span>{{ item.label }}</span>

                        <i v-if="hasSubmenu" :class="['pi pi-angle-down ml-auto', { 'pi-angle-down': root, 'pi-angle-right': !root }]"></i>
                    </a>
                </template>
                <template #end>
                    <div class="flex items-center gap-2">
                        <!--                    <Avatar image="https://primefaces.org/cdn/primevue/images/avatar/amyelsner.png" shape="circle" />-->
                    </div>
                </template>
            </Menubar>
        </div>
        <div class="p-5 bg-gray-100">
            <slot />
        </div>


</template>
