import axios from 'axios';
import { defineStore } from 'pinia';

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
        async initDashboard() {
            await Promise.all([
                this.fetchStatusOptions(),
                this.fetchClients()
            ]);
        },

        async fetchStatusOptions() {
            const { data } = await axios.get('/api/enums/status');
            this.statusOptions = data;
        },
        
        async fetchClients(page = 1) {
            try {
                this.loading = true;
            const { data } = await axios.get(`/api/clients`, {
                    params: { 
                        page, 
                        search: this.filters.search, 
                        status: this.filters.status 
                        }
                    });
            this.clients = data;
            } catch (error) {
                if (error.response?.status === 401) {
                    window.location.href = '/login';
                }
            }finally {
                this.loading = false;
            }
        },
        async storeClient(clientData) {
            return await axios.post('/api/clients', clientData);
        },
        async deleteClient(id) {
            return await axios.delete(`/api/clients/${id}`);
        }
    }
});