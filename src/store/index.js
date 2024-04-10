import { createStore } from 'vuex';
import account from './modules/account';

const store = createStore({
    modules: {
        account
    },
    state: {
        theme: localStorage.getItem('theme') || 'light', // Retrieve theme from localStorage if available
        darkMode: localStorage.getItem('darkMode') === 'true' // Retrieve darkMode from localStorage if available
    },
    mutations: {
        setTheme(state, theme) {
            state.theme = theme;
            localStorage.setItem('theme', theme); // Store theme in localStorage
        },
        setDarkMode(state, darkMode) {
            state.darkMode = darkMode;
            localStorage.setItem('darkMode', darkMode); // Store darkMode in localStorage
        }
    },
    getters: {
        currentTheme(state) {
            return state.theme;
        }
    }
});

export default store;
