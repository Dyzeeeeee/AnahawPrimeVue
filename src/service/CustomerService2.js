import ApiService from './ApiService';

const SessionService = {
    async getAllCustomers() {
        try {
            const response = await ApiService.get('getAllCustomers');
            return response.data;
        } catch (error) {
            console.error('Error fetching menu items:', error);
            throw error;
        }
    },
    async addCustomer(newCustomer) {
        try {
            const response = await ApiService.post('addCustomer', newCustomer);
            return response.data;
        } catch (error) {
            console.error('Error adding menu item:', error);
            throw error;
        }
    },
    async archiveCustomer(customerId) {
        try {
            const response = await ApiService.put(`archiveCustomer/${customerId}`);
            return response.data;
        } catch (error) {
            console.error('Error archiving menu item:', error);
            throw error;
        }
    },
    async unarchiveCustomer(customerId) {
        try {
            const response = await ApiService.put(`unarchiveCustomer/${customerId}`);
            return response.data;
        } catch (error) {
            console.error('Error archiving menu item:', error);
            throw error;
        }
    }
};

export default SessionService;
