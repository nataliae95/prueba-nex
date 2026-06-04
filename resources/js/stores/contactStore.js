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
            } catch (error) {
                if (error.response?.status === 401) {
                    window.location.href = '/login';
                }
            }finally {
                this.loading = false;
            }
        },

        
        async updateContact(id, ContactData) {
            return await axios.put(`/api/contacts/${id}`, ContactData);
        },
        
        async createContact(idClient, ContactData) {
            return await axios.post(`/api/clients/${idClient}/contacts`, ContactData);
        },
        
        async deleteContact(contact) {
            const request = await axios.delete(`/api/contacts/${contact.id}`);
            this.fetchContacts(contact.client_id,this.contacts.current_page);
            return request;
        }
    }
});