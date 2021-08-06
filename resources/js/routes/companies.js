import Index from '../components/pages/Companies/Index'
import Show from '../components/pages/Companies/Show'
import Create from '../components/pages/Companies/Create'
import Edit from '../components/pages/Companies/Edit'

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
        path: '/companies/:id/edit',
        name: 'companies.edit',
        component: Edit
    },

    {
        path: '/companies/:id',
        name: 'companies.show',
        component: Show
    },

]