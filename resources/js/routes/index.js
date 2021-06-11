import VueRouter from 'vue-router'

export default new VueRouter({
    routes: [
        {
            path: '/',
            name: 'home',
        }
    ],
    mode: 'history'
})