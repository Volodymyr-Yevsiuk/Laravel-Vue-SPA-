import Index from '../components/pages/Products/Index'
import Show from '../components/pages/Products/Show'

export default [
    {
        path: '/products',
        name: 'products.index',
        component: Index
    },

    {
        path: '/products/:id',
        name: 'products.show',
        component: Show
    }
]