import ApiService from './ApiService';

const CaetgoryService = {
    async getAllCategories() {
        try {
            const response = await ApiService.get('getAllCategories');
            return response.data;
        } catch (error) {
            console.error('Error fetching menu items:', error);
            throw error;
        }
    },
    async addCategory(newCategory) {
        try {
            const response = await ApiService.post('addCategory', newCategory);
            return response.data;
        } catch (error) {
            console.error('Error adding menu item:', error);
            throw error;
        }
    }
};

export default CaetgoryService;
