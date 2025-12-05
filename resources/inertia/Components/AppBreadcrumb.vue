<script setup>
import Breadcrumb from 'primevue/breadcrumb'
import { Link } from '@inertiajs/vue3'
import useAppBreadcrumbStore from "@/inertia/Stores/useAppBreadcrumbStore.js";
import {storeToRefs} from "pinia";

const home = {
    icon: 'pi pi-home',
    route: '/',
}

const store = useAppBreadcrumbStore();
const { items } = storeToRefs(store);
</script>

<template>
        <Breadcrumb :home="home" :model="items">
            <template #item="{ item, props }">
                <Link
                    v-if="item.route"
                    :href="item.route"
                    v-bind="props.action"
                    class="flex items-center gap-2"
                >
                    <i :class="item.icon"></i>
                    <span>{{ item.label }}</span>
                </Link>
                <span
                    v-else
                    class="flex items-center opacity-70"
                    v-bind="props.action"
                >
                    <i :class="item.icon"></i>
                    <span>{{ item.label }}</span>
                </span>
            </template>
        </Breadcrumb>
</template>
