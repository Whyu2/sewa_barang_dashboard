import { createApp, h } from 'vue'
import { createInertiaApp, router } from '@inertiajs/vue3'
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
import Chart from 'primevue/chart';
import DatePicker from 'primevue/datepicker';



createInertiaApp({
    resolve: name =>
        resolvePageComponent(`./Modules/${name}.vue`, import.meta.glob('./Modules/**/*.vue')),
    setup({ el, App, props, plugin }) {
        const pinia = createPinia()
        const app = createApp({ render: () => h(App, props) });
        app.use(plugin);
        app.use(pinia);
        // Guard sinkron: tanpa token langsung ke /login sebelum halaman sempat render.
        // Cek validitas token (basi/kadaluarsa) tetap via /me di DashboardLayout.
        const isPublicPath = (url) => {
            try {
                const path = new URL(url, window.location.origin).pathname;
                return path === '/login';
            } catch {
                return false;
            }
        };
        const hasToken = () => !!localStorage.getItem('access_token');
        router.on('before', (event) => {
            if (!hasToken() && !isPublicPath(event.detail.visit.url.href)) {
                event.preventDefault();
                router.visit('/login');
            }
        });
        // Pengaman tambahan: bila user menghapus token lalu menekan back,
        // halaman dari cache tetap langsung dilempar ke /login.
        router.on('navigate', (event) => {
            if (!hasToken() && !isPublicPath(event.detail.page.url)) {
                router.visit('/login');
                return;
            }
            queryClient.invalidateQueries()
        })
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
        app.component('Chart', Chart);
        app.component('DatePicker', DatePicker);

        // 🔥 REGISTER DIRECTIVES
        app.directive('ripple', Ripple);
        app.directive('styleclass', StyleClass);



        app.mount(el);
    },
});
