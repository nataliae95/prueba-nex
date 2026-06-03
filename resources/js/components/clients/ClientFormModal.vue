<template>
<div class="modal fade show d-block" tabindex="-1" style="background: rgba(0,0,0,0.5);">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content border-0 shadow">

            <div class="modal-header bg-light">
                <h5 class="modal-title fw-bold">{{ isEditing ? 'Editar Cliente' : 'Nuevo Cliente' }}</h5>
                <button type="button" class="btn-close" @click="$emit('close')"></button>
            </div>

            <div class="modal-body p-4">
                <div class="mb-3">
                    <label class="form-label text-muted small">Nombre del Cliente</label>
                    <input v-model="form.name" type="text" class="form-control" />
                </div>

                <div class="mb-3">
                    <label class="form-label text-muted small">NIT / Identificación</label>
                    <input v-model="form.taxId" type="text" class="form-control" />
                </div>

                <div class="mb-3">
                    <label class="form-label text-muted small">Estado</label>
                    <select v-model="form.status" class="form-select">
                        <option v-for="opt in statusOptions" :key="opt.value" :value="opt.value">
                            {{ opt.label }}
                        </option>
                    </select>
                </div>
            </div>

            <div class="modal-footer bg-light">
                <button type="button" class="btn btn-outline-secondary" @click="$emit('close')">Cancelar</button>
                <button type="button" class="btn btn-primary px-4" @click="save" :disabled="loading">
                    {{ loading ? 'Guardando...' : 'Guardar' }}
                </button>
            </div>

        </div>
    </div>
</div>
</template>

<script setup>
import {
    ref,
    reactive,
    computed
} from 'vue';
import {
    useClientStore
} from "@/stores/clientStore";

const props = defineProps(['client', 'statusOptions']);
const emit = defineEmits(['close', 'saved']);
const store = useClientStore();
const loading = ref(false);

const isEditing = computed(() => !!props.client);

const form = reactive(props.client ? {
    ...props.client
} : {
    name: '',
    taxId: '',
    status: 'prospecto'
});

const save = async () => {
    loading.value = true;

    try {
        let response;
        if (isEditing.value) {
            response = await store.updateClient(form.id, form);
        } else {
            response = await store.createClient(form);
        }
        alert(response.data.message);
        emit('saved');

    } catch (error) {
        if (error.response?.status === 422) {
            const validationErrors = error.response.data.errors;
            
            const firstFieldName = Object.keys(validationErrors)[0];
            const errorMessage = validationErrors[firstFieldName][0];
            
            alert(`Error: ${errorMessage}`);
        } else {
            alert(error.response?.data?.message || "Ocurrió un error inesperado.");
        }
    } finally {
        loading.value = false;
    }
};
</script>
