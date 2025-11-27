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
createInertiaApp({
    resolve: name =>
        resolvePageComponent(`./Modules/${name}.vue`, import.meta.glob('./Modules/**/*.vue')),
    setup({ el, App, props, plugin }) {

        const app = createApp({ render: () => h(App, props) });

        app.use(plugin);
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
