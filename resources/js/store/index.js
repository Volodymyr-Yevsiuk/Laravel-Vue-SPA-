import Vue from 'vue'
import Vuex from 'vuex'

Vue.use(Vuex)

const store = new Vuex.Store({
    state: {
        user: {
            current: null,
        },
        isAdmin: false
    },

    mutations: {
        setCurrentUser (state, authUser) {
            state.user.current = authUser
        },
        createAuthUserCompany (state, company) {
            state.user.current.company[Object.keys(state.user.current.company).length] = company
        },
        deleteAuthUserCompany (state, company) {
            Object.keys(state.user.current.company).find(key => { 
                if (state.user.current.company[key].id === company.id) {
                    let index = key
                    state.user.current.company.splice(index, 1)
                } 
            }) 
            
        },
        deleteSomeCompanies (state, companies) {
            companies.forEach(company => {
                for (let key in state.user.current.company) {
                    if (state.user.current.company[key].id === company.id) {
                        let index = key
                        state.user.current.company.splice(index, 1)
                    } 
                }
            })
        },  
        updateAuthUserCompany (state, company) {
            let index = null   
            Object.keys(state.user.current.company).find(key => { 
                if (state.user.current.company[key].id === company.id) {
                    index = key
                    Object.assign(state.user.current.company[index], company)
                } 
            })
        },
        changeIsAdmin (state, value) {
            state.isAdmin = value
        }
    }
})

export default store