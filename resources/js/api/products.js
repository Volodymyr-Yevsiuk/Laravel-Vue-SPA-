import axios from 'axios'

export const loadProducts = async function (query) {
    return await axios({
        method: 'get',
        url: '/api/products',
        params: query
    })
} 

export const loadProduct = async function (id) {
    return await axios({
        method: 'get',
        url: `/api/products/${id}`
    })
}

export const storeProduct = async function (formdata, config) {
    return await axios.post('/api/products', formdata, config)
}

export const updateProduct = async function (formdata, config, id) {
    return await axios.post(`/api/products/${id}`, formdata, config)
}

export const destroyProduct = async function (id) {
    return await axios.delete(`/api/products/${id}`)
}