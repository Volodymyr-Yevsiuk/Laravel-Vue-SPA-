import Index from '../components/pages/Users/Index'
import Show from '../components/pages/Users/Show'

export default [
    {
        path: '/users',
        name: 'users.index',
        component: Index
    },
    {
        path: '/users/:id',
        name: 'users.show',
        component: Show
    }
]
