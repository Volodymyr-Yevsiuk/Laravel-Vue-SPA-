import VueRouter from 'vue-router'

export default new VueRouter({
    routes: [
        {
            path: '/',
            name: 'home',
        },

        ...require('./products').default,
        ...require('./companies').default,
        ...require('./auth').default,
        ...require('./admin').default,
        ...require('./errors').default
    ],
    mode: 'history'
})