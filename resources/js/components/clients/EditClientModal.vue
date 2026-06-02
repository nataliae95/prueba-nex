<template>
    <div v-if="show" class="modal d-block" style="background: rgba(0,0,0,0.5);">
      <div class="modal-dialog">
        <div class="modal-content">
          <div class="modal-header">
            <h5 class="modal-title">Editar Cliente</h5>
            <button @click="$emit('close')" class="btn-close"></button>
          </div>
          <div class="modal-body">
            <input v-model="form.name" class="form-control mb-2" placeholder="Nombre">
            <input v-model="form.nit" class="form-control mb-2" placeholder="NIT">
            <select v-model="form.status" class="form-select">
              <option value="activo">Activo</option>
              <option value="inactivo">Inactivo</option>
            </select>
          </div>
          <div class="modal-footer">
            <button @click="$emit('close')" class="btn btn-secondary">Cancelar</button>
            <button @click="save" class="btn btn-primary">Guardar Cambios</button>
          </div>
        </div>
      </div>
    </div>
  </template>
  
  <script setup>
  import { reactive, watch } from 'vue';
  
  const props = defineProps(['show', 'client']);
  const emit = defineEmits(['close', 'saved']);
  
  // Copiamos los datos del cliente para editar sin afectar el original hasta guardar
  const form = reactive({ ...props.client });
  
  // Sincronizamos si el cliente cambia
  watch(() => props.client, (newVal) => {
    Object.assign(form, newVal);
  });
  
  const save = async () => {
    await axios.put(`/api/clientes/${form.id}`, form);
    emit('saved', form); // Avisamos al padre
}