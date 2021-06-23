import VueRouter from 'vue-router'

export default new VueRouter({
    routes: [
        {
            path: '/',
            name: 'home',
        },

        ...require('./products').default,
        ...require('./companies').default,
        ...require('./users').default,
        ...require('./auth').default
    ],
    mode: 'history'
})