<template>
    <div class="container-fluid p-4">
        <header class="d-flex justify-content-between align-items-center mb-4">
            <h4 class="fw-bold text-secondary">Gestión de Contactos</h4>
            <div class="d-flex gap-2">
                <input v-model="store.filters.search" @input="handleSearch" type="text" class="form-control" placeholder="Buscar..." />
                <select v-model="store.filters.position" @change="store.fetchContacts(props.clientId,1)" class="form-select">
                    <option value="">Todos los estados</option>
                    <option v-for="opt in store.positionOptions" :key="opt.value" :value="opt.value">
                        {{ opt.label }}
                    </option>
                </select>

                <button @click="openModalForm(null)" class="btn btn-primary d-flex align-items-center gap-2">
                    <i class="bi bi-plus-lg"></i>
                    <span>Nuevo</span>
                </button>
            </div>
        </header>

        <section class="card border-0 shadow-sm rounded-3" v-if="store.contacts">
            <div class="card-body p-0">
                <ContactTable 
                    :contacts="store.contacts.data"
                    :loading="store.loading"
                    :positionOptions="store.positionOptions"
                    @edit="openModalForm" 
                    @delete="remove" 
                />
            </div>
        </section>
        

        <Pagination  v-if="store.clients?.last_page > 1" :meta="store.clients" @change="changePage" />

        <ContactFormModal 
            v-if="showModal" 
            :contact="contactToEdit"
            :positionOptions="store.positionOptions"
            :clientId="clientId"
            @close="showModal = false" 
            @saved="handleSaved" 
        />
    </div>
</template>

<script setup>
import { useContactStore } from "@/stores/contactStore";
import { onMounted, ref } from "vue";
import Pagination from "../common/Pagination.vue";
import ContactFormModal from "./ContactFormModal.vue";
import ContactTable from "./ContactTable.vue";
import { confirmDelete, notify } from '../../toast';


const props = defineProps(['clientId']);

const store = useContactStore();
const showModal = ref(false);
const contactToEdit = ref(null);

const openModalForm = (contact = null) => {
    contactToEdit.value = contact;
    showModal.value = true;
};

const handleSaved = async () => {
    showModal.value = false;
};

let searchTimeout;
const handleSearch = () => {
    clearTimeout(searchTimeout);
    searchTimeout = setTimeout(() => store.fetchContacts(props.clientId, 1), 500);
};

const changePage = (page) => store.fetchContacts(props.clientId, page);

const remove = async (contact) => {
    const confirmed = await confirmDelete(`¿Eliminar a ${contact.name}?`);
    
    if (confirmed) {
            await store.deleteContact(contact);
            notify('success', 'Contacto eliminado correctamente');
    }
};

onMounted(() => store.initDashboard(props.clientId));
</script>