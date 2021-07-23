import axios from 'axios'

export const loadUsers = async function (query) {
    return await axios({
        method: 'get',
        url: '/api/users',
        params: {
            page: query
        }
    })
} 

export const loadUser = async function (id) {
    return await axios({
        method: 'get',
        url: `/api/users/${id}`
    })
}

export const destroyUser = async function (id) {
    return await axios.delete(`/api/users/${id}`)
}
