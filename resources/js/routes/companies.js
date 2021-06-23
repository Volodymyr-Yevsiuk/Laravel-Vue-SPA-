import Index from '../components/pages/Companies/Index'
import Show from '../components/pages/Companies/Show'
import Create from '../components/pages/Companies/Create'

export default [
    {
        path: '/companies',
        name: 'companies.index',
        component: Index
    },

    {
        path: '/companies/create',
        name: 'companies.create',
        component: Create
    },

    {
        path: '/companies/:id',
        name: 'companies.show',
        component: Show
    },

]