<template>
    <div class="modal fade show d-block" tabindex="-1" style="background: rgba(0,0,0,0.5);">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content border-0 shadow">
                <div class="modal-header bg-light">
                    <h5 class="modal-title fw-bold">
                        {{ isEditing ? 'Editar Contacto' : 'Nuevo Contacto' }}
                    </h5>
                    <button type="button" class="btn-close" @click="$emit('close')"></button>
                </div>

                <div class="modal-body p-4">
                    <div class="mb-3">
                        <label class="form-label small fw-bold">Nombre</label>
                        <input v-model="form.name" type="text" class="form-control" placeholder="Nombre completo">
                    </div>

                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <label class="form-label small fw-bold">Email</label>
                            <input v-model="form.email" type="email" class="form-control" placeholder="correo@ejemplo.com">
                        </div>
                        <div class="col-md-6 mb-3">
                            <label class="form-label small fw-bold">Teléfono</label>
                            <input v-model="form.phone" type="text" class="form-control" placeholder="Ej. 300 123 4567">
                        </div>
                    </div>

                    <div class="mb-3">
                        <label class="form-label small fw-bold">Cargo / Posición</label>
                        <input v-model="form.position" type="text" class="form-control" placeholder="Ej. Gerente de Ventas">
                    </div>

                    <div class="form-check form-switch">
                        <input v-model="form.is_primary" type="checkbox" class="form-check-input" id="isPrimary">
                        <label class="form-check-label" for="isPrimary">Es el contacto principal</label>
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
    useContactStore
} from "@/stores/contactStore";

const props = defineProps(['contact', 'statusOptions']);
const emit = defineEmits(['close', 'saved']);
const store = usecontactStore();
const loading = ref(false);

const isEditing = computed(() => !!props.contact);

const form = reactive(props.contact ? {
    ...props.contact
} : {
    name: '',
    email: '',
    phone: '',
    position: '',
    is_primary: false,
});

const save = async () => {
    loading.value = true;

    try {
        let response;
        if (isEditing.value) {
            response = await store.updatecontact(form.id, form);
        } else {
            response = await store.createcontact(form);
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
