<template>
    <div class="modal fade show d-block" tabindex="-1" style="background: rgba(0,0,0,0.5);">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content border-0 shadow">
    
                <div class="modal-header bg-light">
                    <h5 class="modal-title fw-bold">{{ isEditing ? 'Editar Contacto' : 'Nuevo Contacto' }}</h5>
                    <button type="button" class="btn-close" @click="$emit('close')"></button>
                </div>
    
                <div class="modal-body p-4">
                    <div class="mb-3">
                        <label class="form-label text-muted small">Nombre</label>
                        <input v-model="form.name" type="text" class="form-control" />
                    </div>
    
                    <div class="mb-3">
                        <label class="form-label text-muted small">Email</label>
                        <input v-model="form.email" type="text" class="form-control" />
                    </div>

                    <div class="mb-3">
                        <label class="form-label text-muted small">Telefono</label>
                        <input v-model="form.phone" type="text" class="form-control" />
                    </div>
    
                    <div class="mb-3">
                        <label class="form-label text-muted small">Cargo</label>
                        <select v-model="form.position" class="form-select">
                            <option v-for="opt in positionOptions" :key="opt.value" :value="opt.value">
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
        useContactStore
    } from "@/stores/contactStore";
    import {
        computed,
        reactive,
        ref
    } from 'vue';
    
    const props = defineProps(['client', 'statusOptions']);
    const emit = defineEmits(['close', 'saved']);
    const store = useContactStore();
    const loading = ref(false);
    
    const isEditing = computed(() => !!props.client);
    
    const form = reactive(props.client ?{
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
                response = await store.updateContact(form.id, form);
            } else {
                response = await store.createContact(form);
            }
            alert(response.data.message);
            emit('saved');
    
        } catch (error) {
            if (error.response ?.status === 422) {
                const validationErrors = error.response.data.errors;
    
                const firstFieldName = Object.keys(validationErrors)[0];
                const errorMessage = validationErrors[firstFieldName][0];
    
                alert(`Error: ${errorMessage}`);
            } else {
                alert(error.response ?.data ?.message || "Ocurrió un error inesperado.");
            }
        } finally {
            loading.value = false;
        }
    };
    </script>
    