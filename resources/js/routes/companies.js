import Index from '../components/pages/Companies/Index'
import Show from '../components/pages/Companies/Show'

export default [
    {
        path: '/companies',
        name: 'companies.index',
        component: Index
    },

    {
        path: '/companies/:id',
        name: 'companies.show',
        component: Show
    }
]