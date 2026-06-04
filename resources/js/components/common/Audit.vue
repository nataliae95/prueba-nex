<template>
    <div class="bg-white p-4 rounded-4 shadow-sm border h-100" v-if="props.data">
        <h6 class="text-secondary text-uppercase small fw-bold mb-3">Auditoría</h6>
        <ul class="list-unstyled mb-0">
            <li class="mb-3 d-flex justify-content-between border-bottom pb-2">
                <span class="text-muted"><i class="bi bi-calendar-plus me-2"></i>Creado</span>
                <span class="fw-semibold">{{ props.data.created_at }}</span>
            </li>
            <li class="mb-3 d-flex justify-content-between border-bottom pb-2">
                <span class="text-muted"><i class="bi bi-pencil me-2"></i>Editado</span>
                <span class="fw-semibold">{{ props.data.updated_at }}</span>
            </li>
            <li class="d-flex justify-content-between">
                <span class="text-muted"><i class="bi bi-person-badge me-2"></i>Responsable</span>
                <span class="fw-semibold">{{ props.data.creator?.name || 'Sistema' }}</span>
            </li>
        </ul>
    </div>
</template>

    
<script setup>
    const props = defineProps(['data']);
</script>
