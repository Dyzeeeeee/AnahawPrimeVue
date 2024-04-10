const userData = JSON.parse(localStorage.getItem('user'));

const state = {
    user_id: userData ? userData.user_id : null,
    email: userData ? userData.email : '',
    email: userData ? userData.phone : '',
    name: userData ? userData.name : '',
    isLoggedIn: userData ? true : false
};

const mutations = {
    setUser(state, userData) {
        state.user_id = userData.user_id;
        state.email = userData.email;
        state.phone = userData.phone;
        state.name = userData.name;
        state.isLoggedIn = true;
        localStorage.setItem('user', JSON.stringify(userData));
    },
    logout(state) {
        state.user_id = null;
        state.email = '';
        state.phone = '';
        state.name = '';
        state.isLoggedIn = false;
        localStorage.removeItem('user');
    }
};

const actions = {
    setUser({ commit }, userData) {
        commit('setUser', userData);
    },
    logout({ commit }) {
        commit('logout');
    }
};

const getters = {
    user: (state) => ({
        user_id: state.user_id,
        email: state.email,
        phone: state.phone,
        name: state.name,
        isLoggedIn: state.isLoggedIn
    })
};

export default {
    state,
    mutations,
    actions,
    getters
};
