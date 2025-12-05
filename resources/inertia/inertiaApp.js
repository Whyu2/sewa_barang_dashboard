import { createApp, h } from 'vue'
import { createInertiaApp } from '@inertiajs/vue3'
import PrimeVue from 'primevue/config';
import Aura from '@primeuix/themes/aura';
import { resolvePageComponent } from "laravel-vite-plugin/inertia-helpers";
import 'primeicons/primeicons.css';
import Drawer from 'primevue/drawer';
import Button from 'primevue/button';
import Ripple from 'primevue/ripple';
import StyleClass from 'primevue/styleclass';
import Avatar from 'primevue/avatar';
import {Menubar} from "primevue";
import './Assets/main.css';
import { createPinia } from 'pinia';
import { QueryClient, VueQueryPlugin } from '@tanstack/vue-query';
import router from './Routers/index.js'
import DialogService from 'primevue/dialogservice';

// global config for tanstack query
const queryClient = new QueryClient({
    defaultOptions: {
        queries: {
            retry: false,
            refetchOnWindowFocus: false,
            staleTime: 5 * 60 * 1000, // 5 Minutes
        },
    },
});


createInertiaApp({
    resolve: name =>
        resolvePageComponent(`./Modules/${name}.vue`, import.meta.glob('./Modules/**/*.vue')),
    setup({ el, App, props, plugin }) {
        const pinia = createPinia()
        const app = createApp({ render: () => h(App, props) });
        app.use(plugin);
        app.use(pinia);
        app.use(router);
        app.use(DialogService);
        app.use(VueQueryPlugin, {
            queryClient,
        })
        app.use(PrimeVue, {
            theme: {
                preset: Aura,
                options: {
                    prefix: 'p',
                    darkModeSelector: '.p-dark',
                    cssLayer: false,
                },
            },
        });
        app.component('Drawer', Drawer);
        app.component('Button', Button);
        app.component('Avatar', Avatar);
        app.component('Menubar', Menubar);

        // 🔥 REGISTER DIRECTIVES
        app.directive('ripple', Ripple);
        app.directive('styleclass', StyleClass);

        app.mount(el);
    },
});
