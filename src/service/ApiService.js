import axios from 'axios';

const API_URL = 'http://Capstone.test/AnahawBE/public';

const ApiService = {
    get(resource) {
        return axios.get(`${API_URL}/${resource}`);
    },
    post(resource, data) {
        return axios.post(`${API_URL}/${resource}`, data);
    },
    put(resource, data) {
        return axios.put(`${API_URL}/${resource}`, data);
    },
 
};

export default ApiService;
