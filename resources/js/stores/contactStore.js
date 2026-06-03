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
        filters: { search: '', position: '' }
    }),
    actions: {
        async initDashboard($id) {
            await Promise.all([
                this.fetchPositionOptions(),
                this.fetchContacts($id) 
            ]);
        },

        async fetchPositionOptions() {
            const { data } = await axios.get('/api/enums/positions');
            this.positionOptions = data;
        },
        
        async fetchContacts(id, page = 1) {
            try {
                this.loading = true;
            const { data } = await axios.get(`/api/clients/${id}/contacts/`, {
                    params: { 
                        page, 
                        search: this.filters.search, 
                        position: this.filters.position 
                        }
                    });
            this.contacts = data;
            console.log(this.contacts);
            } catch (error) {
                console.log(error);
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