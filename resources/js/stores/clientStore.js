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
        client: null,
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

        async fetchClient(id) {
            this.loading = true;
            try {
                const { data } = await axios.get(`/api/clients/${id}`);
                this.client = data.data; 
            } catch (error) {
                throw error;
            } finally {
                this.loading = false;
            }
        },

        async _handleRequest(requestFn) {
            const response = await requestFn();
            await this.fetchClients(this.clients.current_page);
            return response;
        },

        async updateClient(id, clientData) {
            return await this._handleRequest(() => axios.put(`/api/clients/${id}`, clientData));
        },

        async createClient(clientData) {
            return await this._handleRequest(() => axios.post('/api/clients', clientData));
        },

        async deleteClient(id) {
            return await this._handleRequest(() => axios.delete(`/api/clients/${id}`));
        }
    }
});