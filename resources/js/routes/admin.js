import Admin from '../components/pages/Admin/AdminPanel'
import UsersIndex from '../components/pages/Admin/Users/Index'
import UsersShow from '../components/pages/Admin/Users/Show'
import ProductsIndex from '../components/pages/Admin/Products/Index'
import CompaniesIndex from '../components/pages/Admin/Companies/Index'

export default [
    {
        path: '/admin',
        name: 'admin.index',
        component: Admin
    },

    {
        path: '/admin/users',
        name: 'admin.users.index',
        component: UsersIndex
    },

    {
        path: '/admin/users/:id',
        name: 'admin.users.show',
        component: UsersShow
    },

    {
        path: '/admin/products',
        name: 'admin.products.index',
        component: ProductsIndex
    },

    {
        path: '/admin/companies',
        name: 'admin.companies.index',
        component: CompaniesIndex
    }

]