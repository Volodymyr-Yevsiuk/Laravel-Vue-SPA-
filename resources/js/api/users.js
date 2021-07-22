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
