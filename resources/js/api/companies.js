import axios from 'axios'

export const loadCompanies = async function (query) {
    return await axios({
        method: 'get',
        url: '/api/companies',
        params: {
            page: query
        }
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