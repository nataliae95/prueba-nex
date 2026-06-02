import './bootstrap';
import { createApp } from 'vue';
import { createPinia } from 'pinia';
import 'bootstrap/dist/css/bootstrap.min.css';
import 'bootstrap-icons/font/bootstrap-icons.css';


// Componentes
import prueba from './components/prueba.vue';
import ClientList from './components/clients/ClientList.vue';

const app = createApp({});

const pinia = createPinia();

app.use(pinia);

app.component('prueba', prueba);
app.component('client-list', ClientList);

app.mount('#app');
