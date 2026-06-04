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

                    <div class="mb-3">
                        <div class="form-check form-switch">
                            <label class="form-check-label fw-semibold" for="isPrimary">Principal </label>
                            <input v-model="form.is_primary" class="form-check-input" type="checkbox" :true-value="1" :false-value="0">
                        </div>
                        <small class="text-muted">
                            Marque esta opción si desea establecer este registro como principal.
                        </small>
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
    import { notify } from '../../toast';
    
    const props = defineProps(['contact', 'positionOptions', 'clientId']);
    const emit = defineEmits(['close', 'saved']);
    const store = useContactStore();
    const loading = ref(false);
    
    const isEditing = computed(() => !!props.contact);
    
    const form = reactive(props.contact ?{
        ...props.contact
    } : {
        name: '',
        email: '',
        phone: '',
        position: '',
        is_primary: false
    });
    
    const save = async () => {
        loading.value = true;
    
        try {
            let response;
            if (isEditing.value) {
                response = await store.updateContact(form.id, form);
            } else {
                response = await store.createContact(props.clientId, form);
            }
            store.fetchContacts(props.clientId);
            notify('success', response.data.message);
            emit('saved');
            
    
        } catch (error) {
            if (error ?.status === 422) {
                const validationErrors = error.response.data.errors;
    
                const firstFieldName = Object.keys(validationErrors)[0];
                const errorMessage = validationErrors[firstFieldName][0];
    
                notify('error', errorMessage);
            } else {
                notify('error', error.response ?.data ?.message || "Ocurrió un error inesperado.");
            }
        } finally {
            loading.value = false;
        }
    };
    </script>
    