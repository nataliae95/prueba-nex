<script setup>
import { ref } from 'vue';

const title = ref('¡Vue 3 con Composition API funcionando en Blade!');
</script>

<template>
    <div class="p-6 bg-white rounded-lg shadow-md mt-4">
        <h2 class="text-xl font-bold text-gray-800">{{ title }}</h2>
        <p class="text-gray-600 mt-2">Este componente está listo para convertirse en el CRUD del CRM.</p>
    </div>
</template>