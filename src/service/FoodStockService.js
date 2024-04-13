import ApiService from './ApiService';

const FoodStockService = {
    async getAllFoodStocks() {
        try {
            const response = await ApiService.get('getAllFoodStocks');
            return response.data;
        } catch (error) {
            console.error('Error fetching menu items:', error);
            throw error;
        }
    },
};

export default FoodStockService;
