import { createApp } from 'vue';
import './style.css';
import App from './App.vue';
import { createPinia } from 'pinia';

const VueApp = createApp(App);
const pinia = createPinia();

VueApp.use(pinia);
VueApp.mount('#app')
