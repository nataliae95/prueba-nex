import axios from 'axios';
import { defineStore } from 'pinia';

export const useContactStore = defineStore('Contact', {
    state: () => ({
        Contacts: {
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
                this.fetchContacts()
            ]);
        },

        async fetchPositionOptions() {
            const { data } = await axios.get('/api/enums/positions');
            this.statusOptions = data;
        },
        
        async fetchContacts(page = 1) {
            try {
                this.loading = true;
            const { data } = await axios.get(`/api/Contacts`, {
                    params: { 
                        page, 
                        search: this.filters.search, 
                        status: this.filters.status 
                        }
                    });
            this.Contacts = data;
            } catch (error) {
                if (error.response?.status === 401) {
                    window.location.href = '/login';
                }
            }finally {
                this.loading = false;
            }
        },
        async _handleRequest(requestFn) {
            const response = await requestFn();
            await this.fetchContacts(this.Contacts.current_page);
            return response;
        },

        async updateContact(id, ContactData) {
            return await this._handleRequest(() => axios.put(`/api/Contacts/${id}`, ContactData));
        },

        async createContact(idClient, ContactData) {
            return await this._handleRequest(() => axios.post(`/api/clients/${idClient}/Contacts`, ContactData));
        },

        async deleteContact(id) {
            return await this._handleRequest(() => axios.delete(`/api/Contacts/${id}`));
        }
    }
});