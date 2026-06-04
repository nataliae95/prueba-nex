<template>
<div class="table-responsive">
    <table class="table table-hover align-middle mb-0">
        <thead class="bg-light text-uppercase text-muted small">
            <tr>
                <th class="ps-4 py-3">Nombre</th>
                <th class="d-none d-md-table-cell">NIT</th>
                <th>Estado</th>
                <th class="text-center">Contactos</th>
                <th class="d-none d-lg-table-cell">Creado</th>
                <th class="text-center pe-4">Acciones</th>
            </tr>
        </thead>
        <tbody>
            <tr v-if="loading">
                <td colspan="6" class="text-center py-5">
                    <div class="spinner-border text-primary"></div>
                </td>
            </tr>
            <tr v-else v-for="client in clients" :key="client.id">
                <td class="ps-4 fw-semibold">{{ client.name }}</td>
                <td class="d-none d-md-table-cell text-muted">{{ client.taxId }}</td>
                <td>
                    <span :class="['badge border', getStatusClass(client.status)]">
                        {{ client.status.toUpperCase() }}
                    </span>
                </td>
                <td class="text-center">{{ client.contacts_count }}</td>
                <td class="d-none d-lg-table-cell text-muted small">{{ client.created_at }}</td>
                <td class="text-center pe-4">
                    <a :href="`/clients/${client.id}`" 
                        class="btn btn-sm btn-outline-info border-0 rounded-circle d-inline-flex align-items-center justify-content-center" >
                        <i class="bi bi-eye fs-6"></i>
                    </a>
                    
                    <button type="button" @click="$emit('edit', client)" 
                            class="btn btn-sm btn-outline-primary border-0 rounded-circle d-inline-flex align-items-center justify-content-center" >
                        <i class="bi bi-pencil fs-6"></i>
                    </button>
                    
                    <button type="button" @click="$emit('delete', client)" 
                            class="btn btn-sm btn-outline-danger border-0 rounded-circle d-inline-flex align-items-center justify-content-center" >
                        <i class="bi bi-trash fs-6"></i>
                    </button>
                </td>
            </tr>
        </tbody>
    </table>
</div>
</template>

<script setup>
const props = defineProps(['clients', 'loading', 'statusOptions']);
defineEmits(['edit', 'delete']);

const getStatusClass = (statusValue) => {
    const option = props.statusOptions.find(o => o.value === statusValue);
    return option ? option.style : 'bg-secondary';
};
</script>
