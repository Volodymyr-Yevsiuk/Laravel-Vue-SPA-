import Vue from 'vue'
import Vuex from 'vuex'

Vue.use(Vuex)

const store = new Vuex.Store({
    state: {
        user: {
            current: null,
        }
    },

    mutations: {
        setCurrentUser (state, authUser) {
            state.user.current = authUser
        },
        createAuthUserCompany (state, company) {
            state.user.current.company[Object.keys(state.user.current.company).length] = company
        },
        deleteAuthUserCompany (state, company) {
            let index = null   
            Object.keys(state.user.current.company).find(key => { 
                if (state.user.current.company[key].id === company.id) {
                    index = key
                } 
            })
            delete state.user.current.company[index]
        },
        updateAuthUserCompany (state, company) {
            let index = null   
            Object.keys(state.user.current.company).find(key => { 
                if (state.user.current.company[key].id === company.id) {
                    index = key
                    Object.assign(state.user.current.company[index], company)
                } 
            })
        }
    }
})

export default store