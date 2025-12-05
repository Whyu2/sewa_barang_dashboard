// resources/js/Composables/useBreadcrumb.js
import { ref } from 'vue'

export function useBreadcrumb() {
    const items = ref([])

    function setBreadcrumb(breadcrumbs = []) {
        items.value = breadcrumbs
    }

    return {
        items,
        setBreadcrumb,
    }
}
