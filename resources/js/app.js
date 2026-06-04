import './bootstrap';
import { createApp } from 'vue';
import { createPinia } from 'pinia';
import 'bootstrap/dist/css/bootstrap.min.css';
import 'bootstrap-icons/font/bootstrap-icons.css';


// Componentes
import prueba from './components/prueba.vue';
import ClientList from './components/clients/ClientList.vue';
import ClientShow from './components/clients/ClientShow.vue';


const pinia = createPinia();

// Función de utilidad para montar componentes
const mountIfExist = (selector, component, props = {}) => {
    const el = document.querySelector(selector);
    if (el) {
        const app = createApp(component, props);
        app.use(pinia);
        app.mount(el);
    }
};

// Se ejecuta cuando el DOM está listo
document.addEventListener('DOMContentLoaded', () => {
    mountIfExist('#client-list-app', ClientList);
    // Para el detalle, el ID está en el componente en Blade
    mountIfExist('#client-show-app', ClientShow);
});