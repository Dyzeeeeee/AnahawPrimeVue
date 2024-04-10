import ApiService from './ApiService';

const OrderService1 = {
    async getAllOrders() {
        try {
            const response = await ApiService.get('getAllMenuItems');
            return response.data;
        } catch (error) {
            console.error('Error fetching menu items:', error);
            throw error;
        }
    },
    async createNewOrder(newOrder) {
        try {
            const response = await ApiService.post('createNewOrder', newOrder);
            return response.data;
        } catch (error) {
            console.error('Error creating new order:', error);
            throw error;
        }
    },
    async addCustomerToOrder(orderId, customerData) {
        try {
            const response = await ApiService.put(`addCustomerToOrder/${orderId}`, customerData);
            return response.data;
        } catch (error) {
            console.error('Error adding customer to order:', error);
            throw error;
        }
    },
    async changeService(orderId, serviceData) {
        try {
            const response = await ApiService.put(`changeService/${orderId}`, serviceData);
            return response.data;
        } catch (error) {
            console.error('Error adding customer to order:', error);
            throw error;
        }
    },
    async getOrderDetails(orderId) {
        try {
            const response = await ApiService.get(`getOrderDetails/${orderId}`);
            return response.data;
        } catch (error) {
            console.error('Error fetching order details:', error);
            throw error;
        }
    },
    async getAllSessionOrders(sessionId) {
        try {
            const response = await ApiService.get(`getAllSessionOrders/${sessionId}`);
            return response.data.orders;
        } catch (error) {
            console.error('Error fetching session orders:', error);
            throw error;
        }
    },
    async addMenuItemToOrder(menuItemData) {
        try {
            const response = await ApiService.post(`addMenuItemToOrder`, menuItemData);
            return response.data;
        } catch (error) {
            console.error('Error adding customer to order:', error);
            throw error;
        }
    },
    async getOrderItems(orderId) {
        try {
            const response = await ApiService.get(`getOrderItems/${orderId}`);
            return response.data;
        } catch (error) {
            console.error('Error fetching order details:', error);
            throw error;
        }
    }, 
    
};

export default OrderService1;
