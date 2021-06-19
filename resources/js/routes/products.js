import Index from '../components/pages/Products/Index'
import Show from '../components/pages/Products/Show'
import Create from '../components/pages/Products/Create'

export default [
    {
        path: '/products',
        name: 'products.index',
        component: Index
    },

    {
        path: '/products/create',
        name: 'products.create',
        component: Create
    },

    {
        path: '/products/:id',
        name: 'products.show',
        component: Show
    },

    
]