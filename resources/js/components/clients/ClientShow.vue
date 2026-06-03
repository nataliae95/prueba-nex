<template>
    <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg p-6">
        <div v-if="store.loading" class="text-center py-10">
            <p class="text-gray-500">Cargando datos del cliente...</p>
        </div>

        <div v-else-if="store.client" class="space-y-6">
            <div class="border-b pb-4">
                <h2 class="text-3xl font-bold text-gray-900">{{ store.client.data.name }}</h2>
            </div>
            
            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                <div>
                    <p class="text-sm font-medium text-gray-500">NIT</p>
                    <p class="text-lg text-gray-900">{{ store.client.data.taxId }}</p>
                </div>
                
                <div>
                    <p class="text-sm font-medium text-gray-500">Estado</p>
                    <span :class="[
                        'px-3 py-1 rounded-full text-sm font-semibold',
                        store.client.data.status === 'activo' ? 'bg-green-100 text-green-800' : 'bg-red-100 text-red-800'
                    ]">
                        {{ store.client.data.status }}
                    </span>
                </div>
            </div>
        </div>

        <div v-else class="text-red-500 text-center py-10">
            No se pudo obtener la información.
        </div>
    </div>
</template>

<script setup>
import { onMounted } from 'vue';
import { useClientStore } from '@/stores/clientStore';

const props = defineProps({ clientId: Number });
const store = useClientStore();

onMounted(() => {
    const id = props.clientId || document.getElementById('client-show-app').dataset.id;
    store.fetchClient(id);
});
</script>