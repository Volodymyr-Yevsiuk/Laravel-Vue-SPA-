import Vue from 'vue'
import Vuex from 'vuex'

Vue.use(Vuex)

const store = new Vuex.Store({
    state: {
        user: {
            current: null
        }
    },

    mutations: {
        setCurrentUser(state, authUser) {
            state.user.current = authUser
        }
    }
})

export default store