

<script setup>
import ProgressSpinner from 'primevue/progressspinner'
import { useIsFetching } from '@tanstack/vue-query'
const isFetching = useIsFetching()
</script>

<template>
    <div v-if="isFetching > 0" class="overlay-loader">
        <ProgressSpinner style="width:50px;height:50px" />
    </div>
</template>

<style scoped>
.overlay-loader {
    position: fixed;
    inset: 0;
    background: rgba(0,0,0,.3);
    display: flex;
    align-items: center;
    justify-content: center;
    z-index: 9999;
}
</style>
