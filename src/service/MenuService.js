import ApiService from './ApiService';

const MenuService = {
    async getAllMenuItems() {
        try {
            const response = await ApiService.get('getAllMenuItems');
            return response.data;
        } catch (error) {
            console.error('Error fetching menu items:', error);
            throw error;
        }
    },
    async addMenuItem(newMenuItem) {
        try {
            const response = await ApiService.post('addMenuItem', newMenuItem);
            return response.data;
        } catch (error) {
            console.error('Error adding menu item:', error);
            throw error;
        }
    },
    async archiveMenuItem(menuItemId) {
        try {
            const response = await ApiService.put(`archiveMenuItem/${menuItemId}`);
            return response.data;
        } catch (error) {
            console.error('Error archiving menu item:', error);
            throw error;
        }
    },
    async unarchiveMenuItem(menuItemId) {
        try {
            const response = await ApiService.put(`unarchiveMenuItem/${menuItemId}`);
            return response.data;
        } catch (error) {
            console.error('Error archiving menu item:', error);
            throw error;
        }
    }
};

export default MenuService;
