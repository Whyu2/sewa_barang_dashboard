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
import { Menubar } from "primevue";
import './Assets/main.css';
import { createPinia } from 'pinia';
import { VueQueryPlugin } from '@tanstack/vue-query';
import router from './Routers/index.js'
import DialogService from 'primevue/dialogservice';
import ToastService from 'primevue/toastservice';
import Toast from 'primevue/toast';
import InputText from 'primevue/inputtext';
import InputNumber from 'primevue/inputnumber';
import Image from 'primevue/image';
import Dropdown from 'primevue/dropdown';
import Message from 'primevue/message';
import Checkbox from 'primevue/checkbox';
import CheckboxGroup from 'primevue/checkboxgroup';
import { queryClient, registerApp } from './Composables/QueryClient.js';
import ConfirmationService from 'primevue/confirmationservice';
import Tag from 'primevue/tag';
import Divider from 'primevue/divider';
import FileUpload from 'primevue/fileupload';



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
        app.use(ToastService);
        app.use(ConfirmationService);
        registerApp(app);
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

        app.component('InputText', InputText);
        app.component('Dropdown', Dropdown);
        app.component('InputNumber', InputNumber);
        app.component('Toast', Toast);
        app.component('Message', Message);
        app.component('Drawer', Drawer);
        app.component('Button', Button);
        app.component('Avatar', Avatar);
        app.component('Menubar', Menubar);
        app.component('Image', Image);
        app.component('Tag', Tag);
        app.component('Divider', Divider);
        app.component('Checkbox', Checkbox);
        app.component('CheckboxGroup', CheckboxGroup);
        app.component('FileUpload', FileUpload);

        // 🔥 REGISTER DIRECTIVES
        app.directive('ripple', Ripple);
        app.directive('styleclass', StyleClass);



        app.mount(el);
    },
});
