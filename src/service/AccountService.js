import ApiService from './ApiService';

const AccountService = {
    async createAccount(accountData) {
        try {
            const response = await ApiService.post('createAccount', accountData);
            return response.data; // Assuming your API returns the newly created account data
        } catch (error) {
            console.error('Error creating account:', error);
            throw error; // Rethrow the error to handle it in the component
        }
    },
    async login(credentials) {
        try {
            const response = await ApiService.post('login', credentials);
            return response.data; // Assuming your API returns the authenticated user data
        } catch (error) {
            console.error('Error logging in:', error);
            throw error; // Rethrow the error to handle it in the component
        }
    },
    async logout() {
        try {
            const response = await ApiService.post('logout');
            return response.data; // Assuming your API returns a success message
        } catch (error) {
            console.error('Error logging out:', error);
            throw error; // Rethrow the error to handle it in the component
        }
    },
     async getAccountData() {
        try {
            const response = await ApiService.get('getAccountData');
            return response.data; // Assuming your API returns the user's account data
        } catch (error) {
            console.error('Error fetching account data:', error);
            throw error; // Rethrow the error to handle it in the component
        }
    }
};

export default AccountService;
