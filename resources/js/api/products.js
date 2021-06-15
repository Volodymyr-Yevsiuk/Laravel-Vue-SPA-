import axios from 'axios'

export const loadProducts = async function (query) {
    return await axios({
        method: 'get',
        url: '/api/products',
        params: {
            page: query
        }
    })
} 

export const loadProduct = async function (id) {
    return await axios({
        method: 'get',
        url: `/api/products/${id}`
    })
}