import { defineStore } from 'pinia';
import axios from 'axios';

export const useClientStore = defineStore('client', {
    state: () => ({
        clients: {
            data: [],
            current_page: 1,
            last_page: 1,
            prev_page_url: null,
            next_page_url: null
        },
        loading: false,
        filters: { search: '', status: '' }
    }),
    actions: {
        async fetchClients(page = 1) {
            this.loading = true;
            const { data } = await axios.get(`/api/clients`, {
                    params: { 
                        page, 
                        search: this.filters.search, 
                        status: this.filters.status 
                        }
                    });
            this.clients = data;
            this.loading = false;
        },
        async storeClient(clientData) {
            return await axios.post('/api/clients', clientData);
        },
        async deleteClient(id) {
            return await axios.delete(`/api/clients/${id}`);
        }
    }
});