import VueRouter from 'vue-router'

export default new VueRouter({
    routes: [
        {
            path: '/',
            name: 'home',
        },

        ...require('./products').default,
    ],
    mode: 'history'
})