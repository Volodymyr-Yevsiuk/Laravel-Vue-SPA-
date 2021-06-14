import store from '../store/index'

export default {
    computed: {
        currentAuthorizedUser() {
            store.commit('setCurrentUser', window.auth)
            return store.state.user.current
        }
    }
}