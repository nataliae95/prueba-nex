import './bootstrap';
import { createApp } from 'vue';
import { createPinia } from 'pinia';

import Alpine from 'alpinejs';

// Componentes
import prueba from './components/prueba.vue';

const app = createApp({});
const pinia = createPinia();

app.use(pinia);

// Registramos el componente globalmente para usarlo en cualquier vista Blade
app.component('prueba', prueba);

// Montamos la aplicación de Vue en el elemento con id "app"
app.mount('#app');
