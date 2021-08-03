import axios from 'axios'

export const loadCompanies = async function (query) {
    return await axios({
        method: 'get',
        url: '/api/companies',
        params: query
    })
} 

export const loadCompany = async function (id) {
    return await axios({
        method: 'get',
        url: `/api/companies/${id}`
    })
}

export const storeCompany = async function (formdata, config) {
    return await axios.post('/api/companies', formdata, config)
}

export const updateCompany = async function (formdata, config, id) {
    return await axios.post(`/api/companies/${id}`, formdata, config)
}

export const destroyCompany = async function (id) {
    return await axios.delete(`/api/companies/${id}`)
}

export const loadCompanyProducts = async function (id) {
    return await axios.get(`/api/companies/user/${id}`)
}

export const deleteSelectedCompanies = async function (ids) {
    return await axios.post('/api/companies/selected', ids)
}