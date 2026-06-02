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
            </div>
        </header>

        <section class="card border-0 shadow-sm rounded-3">
            <div class="card-body p-0">
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
                            <tr v-if="store.loading">
                                <td colspan="6" class="text-center py-5">
                                    <div class="spinner-border text-primary"></div>
                                </td>
                            </tr>
                            <tr v-else v-for="client in store.clients.data" :key="client.id">
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
                                    <button @click="openEdit(client)" class="btn btn-sm btn-link text-primary p-1">
                                        <i class="bi bi-pencil-fill"></i>
                                    </button>
                                    <button @click="remove(client.id)" class="btn btn-sm btn-link text-danger p-1">
                                        <i class="bi bi-trash-fill"></i>
                                    </button>
                                </td>
                            </tr>
                        </tbody>
                    </table>
                </div>
            </div>
        </section>

        <Pagination v-if="store.clients.last_page > 1" :meta="store.clients" @change="changePage" />

        <EditClientModal 
            v-if="showModal" 
            :client="clientToEdit" 
            @close="showModal = false" 
            @saved="handleSaved" 
        />
    </div>
</template>

<script setup>
import { ref, onMounted } from "vue";
import { useClientStore } from "@/stores/clientStore";
import EditClientModal from "./EditClientModal.vue";
import Pagination from "@/components/clients/Pagination.vue";

const store = useClientStore();
const showModal = ref(false);
const clientToEdit = ref(null);

const STATUS_STYLES = {
    activo: "bg-success bg-opacity-10 text-success border-success",
    inactivo: "bg-danger bg-opacity-10 text-danger border-danger",
    prospecto: "bg-info bg-opacity-10 text-info border-info",
};

const getStatusClass = (status) => STATUS_STYLES[status] || "bg-secondary text-secondary";

// Lógica de Edición
const openEdit = (client) => {
    clientToEdit.value = { ...client };
    showModal.value = true;
};

const handleSaved = async () => {
    showModal.value = false;
    await store.fetchClients(store.clients.current_page);
};

// Lógica de Búsqueda y Navegación
let searchTimeout;
const handleSearch = () => {
    clearTimeout(searchTimeout);
    searchTimeout = setTimeout(() => store.fetchClients(1), 500);
};

const changePage = (page) => store.fetchClients(page);

const remove = async (id) => {
    if (!confirm("¿Eliminar este registro?")) return;
    await store.deleteClient(id);
    await store.fetchClients(store.clients.current_page);
};

onMounted(() => store.initDashboard());
</script>