<template>
    <div class="container-fluid p-4">
        <header class="d-flex justify-content-between align-items-center mb-4">
            <h4 class="fw-bold text-secondary">Gestión de Clientes</h4>
            <div class="d-flex gap-2">
                <input v-model="store.filters.search" @input="handleSearch" type="text" class="form-control" placeholder="Buscar..." />
                <select v-model="store.filters.status" @change="store.fetchClients(1)" class="form-select">
                    <option value="">Todos los estados</option>
                    <option v-for="opt in store.statusOptions" :key="opt.value" :value="opt.value">
                        {{ opt.label }}
                    </option>
                </select>

                <button @click="openModalForm(null)" class="btn btn-primary d-flex align-items-center gap-2">
                    <i class="bi bi-plus-lg"></i>
                    <span>Nuevo</span>
                </button>
            </div>
        </header>

        <section class="card border-0 shadow-sm rounded-3">
            <div class="card-body p-0">
                <ClientTable 
                    :clients="store.clients.data"
                    :loading="store.loading"
                    :statusOptions="store.statusOptions"
                    @edit="openModalForm" 
                    @delete="remove" 
                />
            </div>
        </section>

        <Pagination v-if="store.clients.last_page > 1" :meta="store.clients" @change="changePage" />

        <ClientFormModal 
            v-if="showModal" 
            :client="clientToEdit"
            :statusOptions="store.statusOptions"
            @close="showModal = false" 
            @saved="handleSaved" 
        />
    </div>
</template>

<script setup>
import { ref, onMounted } from "vue";
import { useClientStore } from "@/stores/clientStore";
import ClientFormModal from "./ClientFormModal.vue";
import Pagination from "./Pagination.vue";
import ClientTable from "./ClientTable.vue";

const store = useClientStore();
const showModal = ref(false);
const clientToEdit = ref(null);


// Lógica de Edición
const openModalForm = (client = null) => {
    clientToEdit.value = client;
    showModal.value = true;
};

const handleSaved = async () => {
    showModal.value = false;
};

let searchTimeout;
const handleSearch = () => {
    clearTimeout(searchTimeout);
    searchTimeout = setTimeout(() => store.fetchClients(1), 500);
};

const changePage = (page) => store.fetchClients(page);

const remove = async (id) => {
    if (!confirm("¿Eliminar este registro?")) return;
    await store.deleteClient(id);
};

onMounted(() => store.initDashboard());
</script>