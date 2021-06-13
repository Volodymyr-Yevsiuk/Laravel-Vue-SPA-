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