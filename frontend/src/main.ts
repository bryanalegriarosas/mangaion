import { createApp } from 'vue';
import App from './App.vue';
import router from './router';
import { createPinia } from 'pinia';
import './style.css';

const VueApp = createApp(App);
const pinia = createPinia();

VueApp.use(pinia);
VueApp.use(router);
VueApp.mount('#app');
