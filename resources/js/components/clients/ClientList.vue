<template>
    <div class="container-fluid p-4">
        <div class="d-flex justify-content-between align-items-center mb-4">
            <h4 class="fw-bold text-secondary">Gestión de Clientes</h4>
            <div class="d-flex gap-2">
                <input
                    v-model="store.filters.search"
                    @input="handleSearch"
                    type="text"
                    class="form-control"
                    placeholder="Buscar..."
                />
                <select
                    v-model="store.filters.status"
                    @change="store.fetchClients(1)"
                    class="form-select"
                >
                    <option value="">Todos los estados</option>
                    <option
                        v-for="opt in store.statusOptions"
                        :key="opt.value"
                        :value="opt.value"
                    >
                        {{ opt.label }}
                    </option>
                </select>
            </div>
        </div>

        <div class="card border-0 shadow-sm rounded-3">
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
                                    <div
                                        class="spinner-border text-primary"
                                        role="status"
                                    ></div>
                                </td>
                            </tr>
                            <tr
                                v-else
                                v-for="client in store.clients.data"
                                :key="client.id"
                            >
                                <td class="ps-4 fw-semibold">
                                    {{ client.name }}
                                </td>
                                <td class="d-none d-md-table-cell text-muted">
                                    {{ client.taxId }}
                                </td>
                                <td>
                                    <span
                                        :class="[
                                            'badge border',
                                            getStatusClass(client.status),
                                        ]"
                                    >
                                        {{ client.status.toUpperCase() }}
                                    </span>
                                </td>
                                <td class="text-center">
                                    {{ client.contacts_count }}
                                </td>
                                <td
                                    class="d-none d-lg-table-cell text-muted small"
                                >
                                    {{ client.created_at }}
                                </td>
                                <td class="text-center pe-4">
                                    <div class="btn-group">
                                        <button
                                            @click="editClient(client)"
                                            class="btn btn-sm btn-link text-primary p-1"
                                        >
                                            <i class="bi bi-pencil-fill"></i>
                                        </button>
                                        <button
                                            @click="remove(client.id)"
                                            class="btn btn-sm btn-link text-danger p-1"
                                        >
                                            <i class="bi bi-trash-fill"></i>
                                        </button>
                                    </div>
                                </td>
                            </tr>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>

        <nav
            v-if="store.clients.last_page > 1"
            class="mt-4"
            aria-label="Navegación de páginas"
        >
            <ul
                class="pagination pagination-sm justify-content-center align-items-center gap-1"
            >
                <li
                    class="page-item"
                    :class="{ disabled: !store.clients.prev_page_url }"
                >
                    <button
                        class="page-link rounded"
                        @click="changePage(store.clients.current_page - 1)"
                        :disabled="!store.clients.prev_page_url"
                    >
                        <i class="bi bi-chevron-left small"></i> Anterior
                    </button>
                </li>

                <li class="page-item mx-2">
                    <span class="text-muted small fw-bold">
                        Pág. {{ store.clients.current_page }} de
                        {{ store.clients.last_page }}
                    </span>
                </li>

                <li
                    class="page-item"
                    :class="{ disabled: !store.clients.next_page_url }"
                >
                    <button
                        class="page-link rounded"
                        @click="changePage(store.clients.current_page + 1)"
                        :disabled="!store.clients.next_page_url"
                    >
                        Siguiente <i class="bi bi-chevron-right small"></i>
                    </button>
                </li>
            </ul>
        </nav>
    </div>
</template>

<script setup>
import { onMounted } from "vue";
import { useClientStore } from "@/stores/clientStore";

const store = useClientStore();

// --- Configuraciones ---
const STATUS_STYLES = {
    activo: "bg-success bg-opacity-10 text-success border-success",
    inactivo: "bg-danger bg-opacity-10 text-danger border-danger",
    prospecto: "bg-info bg-opacity-10 text-info border-info",
};

const getStatusClass = (status) =>
    STATUS_STYLES[status] ||
    "bg-secondary bg-opacity-10 text-secondary border-secondary";

// --- Logica de Busqueda (Debounced) ---
let searchTimeout;
const handleSearch = () => {
    clearTimeout(searchTimeout);
    searchTimeout = setTimeout(() => store.fetchClients(1), 500);
};

// --- logica de Navegación ---
const changePage = (page) => {
    if (page > 0 && page <= store.clients.last_page) {
        store.fetchClients(page);
    }
};

// --- Acciones del CRUD ---
const remove = async (id) => {
    if (!confirm("¿Estás seguro de eliminar este registro?")) return;

    try {
        await store.deleteClient(id);
        await store.fetchClients(store.clients.current_page);
    } catch (error) {
        alert("Error al eliminar el cliente");
    }
};

const editClient = (client) => console.log("Editando:", client);

onMounted(() => store.initDashboard());
</script>
