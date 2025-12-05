import { defineStore } from 'pinia';
import { ref } from 'vue';

const useAppBreadcrumbStore = defineStore('app-breadcrumb-store', () => {
    const items = ref(
      []
    );

    const setItem = (item) => {
        items.value = [...item];
    }

    return {
        items,
        setItem,
    };
});

export default useAppBreadcrumbStore;
