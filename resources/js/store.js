import Vue from 'vue'
import Vuex from 'vuex'
Vue.use(Vuex)

export default new Vuex.Store({
    state: {
        user: false,
    },
    getters: {
        
    },
    mutations: {
        updateUser(state, payload) {
            state.user = payload
        }
    },
    actions: {

    }
})