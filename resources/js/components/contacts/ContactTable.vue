<template>
<div class="table-responsive">
    <table class="table table-hover align-middle mb-0">
        <thead class="bg-light text-uppercase text-muted small">
            <tr>
                <th class="ps-4 py-3">Nombre</th>
                <th class="d-none d-md-table-cell">Email</th>
                <th class="d-none d-lg-table-cell">Telefono</th>
                <th class="d-none d-lg-table-cell">Cargo</th>
                <th class="d-none d-lg-table-cell">Principal</th>
                <th class="text-center pe-4">Acciones</th>
            </tr>
        </thead>
        <tbody>
            <tr v-if="loading">
                <td colspan="6" class="text-center py-5">
                    <div class="spinner-border text-primary"></div>
                </td>
            </tr>
            <tr v-else v-for="contact in contacts" :key="contact.id">
                <td class="ps-4 fw-semibold">{{ contact.name }}</td>
                <td class="d-none d-md-table-cell text-muted">{{ contact.email }}</td>
                <td class="d-none d-lg-table-cell text-muted small">{{ contact.phone }}</td>
                <td class="d-none d-lg-table-cell text-muted small">{{ contact.position }}</td>
                <td class="d-none d-lg-table-cell text-muted small">{{ contact.is_primary }}</td>
                <td class="text-center pe-4">
                    <button @click="$emit('edit', contact)" class="btn btn-sm btn-link text-primary p-1">
                        <i class="bi bi-pencil-fill"></i>
                    </button>
                    <button @click="$emit('delete', contact)" class="btn btn-sm btn-link text-danger p-1">
                        <i class="bi bi-trash-fill"></i>
                    </button>
                </td>
            </tr>
        </tbody>
    </table>
</div>
</template>

    
    
<script setup>
const props = defineProps(['contacts', 'loading']);
defineEmits(['edit', 'delete']);
</script>
