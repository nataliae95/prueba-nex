<template>
  <nav aria-label="breadcrumb" class="mb-3">
    <ol class="breadcrumb">
        <li class="breadcrumb-item">
            <a href="/clients">Clientes</a>
        </li>
        <li class="breadcrumb-item active" aria-current="page">
            Detalle Cliente
        </li>
    </ol>
</nav>
    <div class="card shadow-sm border-0 rounded-4 overflow-hidden">
      <div class="card-header bg-white py-4 border-0 d-flex justify-content-between align-items-center">
        <div>
          <h5 class="mb-1 fw-bold text-dark">Detalles del Cliente</h5>
          <p class="text-muted small mb-0">Gestión y seguimiento de cuenta</p>
        </div>
  
        <div class="d-flex align-items-center gap-3">
          <span v-if="store.client" class="d-flex align-items-center">
            <span :class="['badge rounded-pill px-3 py-2', getStatusClass(store.client.status)]">
              <i class="bi bi-circle-fill me-1 small"></i> {{ store.client.status?.toUpperCase() }}
            </span>
          </span>
          <button @click="openModalForm(store.client)" class="btn btn-primary btn-sm px-3 rounded-pill shadow-sm">
            <i class="bi bi-pencil-square me-1"></i> Editar Cliente
          </button>
        </div>
      </div>
  
      <div class="card-body p-4 bg-light" v-if="store.client">
        
        <div class="row g-4">
          <div class="col-lg-8">
            <div class="bg-white p-4 rounded-4 shadow-sm border h-100">
              <h6 class="text-primary text-uppercase small fw-bold mb-4 tracking-wider">Información General</h6>
              
              <div class="d-flex gap-4 mb-4">
                <div class="bg-primary bg-opacity-10 p-3 rounded-3 text-primary">
                  <i class="bi bi-building fs-4"></i>
                </div>
                <div>
                  <small class="text-muted text-uppercase fw-bold d-block">Nombre / Razón Social</small>
                  <div class="fs-4 fw-bold text-dark">{{ store.client.name }}</div>
                </div>
              </div>
  
              <div class="d-flex gap-4">
                <div class="bg-secondary bg-opacity-10 p-3 rounded-3 text-secondary">
                  <i class="bi bi-hash fs-4"></i>
                </div>
                <div>
                  <small class="text-muted text-uppercase fw-bold d-block">NIT / Identificación</small>
                  <div class="fs-4 fw-bold text-dark">{{ store.client.taxId }}</div>
                </div>
              </div>
            </div>
          </div>
  
          <div class="col-lg-4">
            <Audit :data="store.client"/>
          </div>
        </div>
  
        <div class="mt-4">
          <ContactListShow :clientId="store.client.id" />
        </div>
      </div>
  
      <div v-else class="card-body text-center py-5">
        <div class="spinner-grow text-primary" role="status"></div>
        <p class="text-muted mt-3">Preparando datos...</p>
      </div>
    </div>
    <ClientFormModal 
            v-if="showModal" 
            :client="clientToEdit"
            :statusOptions="store.statusOptions"
            @close="showModal = false" 
            @saved="handleSaved" 
        />
  </template>
  
  <style scoped>
  .tracking-wider { letter-spacing: 0.05em; }
  .rounded-4 { border-radius: 1rem !important; }
  </style>
<script setup>
import { useClientStore } from "@/stores/clientStore";
import { onMounted, ref } from "vue";
import ContactListShow from "../contacts/ContactListShow.vue";
import ClientFormModal from "./ClientFormModal.vue";
import Audit from "../common/Audit.vue";

const props = defineProps({
    clientId: Number
});

const store = useClientStore();

const showModal = ref(false);
const clientToEdit = ref(null);

const openModalForm = (client) => {
    clientToEdit.value = client;
    showModal.value = true;
};

const handleSaved = async () => {
    store.fetchClient(store.client.id);
    showModal.value = false;
};



const getStatusClass = (statusValue) => {
    if (!Array.isArray(store.statusOptions)) {
        return 'bg-secondary';
    }

    const option = store.statusOptions.find(o => o.value === statusValue);
    return option ? option.style : 'bg-secondary';
};

onMounted(() => {
    const id = props.clientId || document.getElementById('client-show-app').dataset.id;
    store.fetchClient(id);
    store.fetchStatusOptions();
});
</script>
