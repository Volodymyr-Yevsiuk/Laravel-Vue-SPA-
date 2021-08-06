<template>
    <div class="text-center">
        <search v-model="searchText" @search="searchFunction"/>
        <vs-button 
            v-if="checked != ''"
            danger
            class="btn"
            @click="showModal(checked)"
        >
            Видалити вибране
        </vs-button>
        <div class="checkAll">
            <label for="checkAll">Вибрати все на сторінці</label>
            <input type="checkbox" 
                name="checkAll" 
                class="checkbox" 
                :checked="checkIfSelectAll()" 
                @change="selectAll($event.target)"
            />
        </div>
        <vs-table>
            <template #thead>
                <vs-tr>
                    <vs-th>Вибрані</vs-th>
                    <vs-th>Фото</vs-th>
                    <vs-th>Ім'я</vs-th>
                    <vs-th>Електронна пошта</vs-th>
                    <vs-th>Має компанії</vs-th>
                    <vs-th>Роль</vs-th>
                    <vs-th>Переглянути</vs-th>
                    <vs-th>Видалити</vs-th>
                </vs-tr>
            </template>
            <template #tbody>
                <spinner v-if="loading"/>
                <vs-tr
                    :key="i"
                    v-for="(user, i) in users"
                    :data="user"
                >
                    <vs-td>
                        <input v-if="currentAuthorizedUser.id != user.id"
                            type="checkbox" 
                            name="ids" 
                            class="checkbox" 
                            :checked="checked.includes(user.id)" 
                            :value="user.id" 
                            @change="selectItem($event.target, user.name)"/>
                    </vs-td>    
                    <vs-td>
                        <img :src="user.profile_photo_path"/> 
                    </vs-td>
                    <vs-td>
                        {{ user.name }}
                    </vs-td>
                    <vs-td>
                        {{ user.email }}
                    </vs-td>
                    <vs-td>
                        {{ (user.company.length  > 0) ? 'Має' : 'Немає'}}
                    </vs-td>
                    <vs-td>
                        {{ user.role.name }}
                    </vs-td>
                    <vs-td>
                        <router-link :to="{name: 'admin.users.show', params: { id: user.id} }">
                            <i class="fas fa-eye"></i>
                        </router-link>
                    </vs-td>
                    <vs-td v-if="currentAuthorizedUser.id != user.id">
                        <i class="fas fa-trash-alt" @click="showModal(user.id, user.name)"></i>
                    </vs-td>
                </vs-tr>
            </template>
            <template #footer>
                <vs-pagination v-if="totalPages > 1" v-model="page" :length="totalPages" @input="changePage" />
            </template>
        </vs-table>
        <delete-modal
            v-if="openModal && (typeof userIdForDelete) != 'object'"
            title="Видалення користувача"
            mainText="Ви дійсно хочете видалити цього користувача:"  
            :deleteFunc="deleteUser" 
            :items="userNameForDelete" 
            :id="userIdForDelete"
            @cancel="cancelModal"
        />
        <delete-modal
            v-if="openModal && (typeof userIdForDelete) == 'object'"
            title="Видалення користувачів"
            mainText="Ви дійсно хочете видалити вибраних користувачів:"  
            :deleteFunc="deleteUsers"  
            :items="selectedUsersNames"
            :id="userIdForDelete"
            @cancel="cancelModal"
        />
    </div>
</template>

<script>
import {loadUsers, destroyUser, deleteSelectedUsers} from '../../../../api/users'
import DeleteModal from '../../../elements/DeleteModal.vue'
import Spinner from '../../../elements/Spinner.vue'
import Search from '../../../elements/Search.vue'

export default {
    components: {
        DeleteModal,
        Spinner,
        Search
    },
    data() {
        return {
            users: [],
            checked: [],
            selectedUsersNames: [],
            usersIds: [],
            usersNames: [],
            page: 1,
            totalPages: 0,
            openModal: false,
            userIdForDelete: null,
            userNameForDelete: '',
            loading: false,
            searchText: ''
        }
    },
    async beforeRouteEnter (to, from, next) {
        
        await next(vm => {

            if (vm.currentAuthorizedUser === null) {
                window.location.href = '/login'
            } else {
                if (vm.currentAuthorizedUser.role.name !== 'Admin') {
                    vm.$router.push({name: 'forbidden'})
                }
            }

            vm.loading = true
            loadUsers()
            .then(response => {
                vm.users = response.data.data
                vm.page = response.data.meta.current_page
                vm.totalPages = response.data.meta.last_page
                vm.loading = false

                vm.users.map(user => {
                    if (vm.currentAuthorizedUser.id != user.id) {
                        vm.usersIds.push(parseInt(user.id))
                        vm.usersNames.push(user.name)
                    }
                })

                Object.keys(localStorage).map(key => {
                    if (key.includes('user')) {
                        const length = 'user-'.length
                        let id = key.slice(length)
                        vm.checked.push(parseInt(id))
                        vm.selectedUsersNames.push(localStorage.getItem(key))
                    }                
                })
            })
            .catch(err => {
                console.error(err)
                vm.loading = true
            })
        })
    },
    methods: {
        async changePage(page = 1) {
            this.loading = true
            await loadUsers ({page : page, q: this.searchText})
                .then((response) => {
                    this.users = response.data.data
                    this.totalPages = response.data.meta.last_page
                    this.page = response.data.meta.current_page
                    this.loading = false
                    this.updateUsersIdsAndNames()
                })
                .catch(err => {
                    console.error(err)
                    this.loading = true
                })
        },
        showModal(id, name) {
            this.userIdForDelete = id
            this.userNameForDelete = name
            this.openModal = true
        },

        cancelModal() {
            this.userIdForDelete = null
            this.userNameForDelete = ''
            this.openModal = false
        },

        async deleteUser(id, user) {
            await destroyUser(id)
                .then( response => {
                    this.loading = true
                    if (this.checked.includes(id) && localStorage.getItem(`user-${id}`) && this.selectedUsersNames.includes(user)) {
                        let index = this.checked.indexOf(id)
                        this.checked.splice(index, 1)
                        index = this.selectedUsersNames.indexOf(user)
                        this.selectedUsersNames.splice(index, 1)
                        localStorage.removeItem(`user-${id}`)
                    }
                    loadUsers({q: this.searchText})
                        .then(response => {
                            this.users = response.data.data
                            this.totalPages = response.data.meta.last_page
                            this.page = response.data.meta.current_page
                            this.loading = false
                            this.updateUsersIdsAndNames()
                        })  
                        .catch(err => {
                            console.error(err)
                            this.loading = true
                        })
                    console.log(`Користувача з ${id} було видалено`)
                })
                .catch(err => {
                    console.error(err)
                })
        },

        async deleteUsers(ids) {
            await deleteSelectedUsers({ids : ids})
                .then(response => {
                    this.loading = true
                    this.checked = []
                    this.selectedUsersNames = []

                    Object.keys(localStorage).map(key => {
                        if (key.includes('user')) {
                            localStorage.removeItem(key)
                        }                
                    })
                    
                    loadUsers({q: this.searchText})
                        .then(response => {
                            this.users = response.data.data
                            this.page = response.data.meta.current_page
                            this.totalPages = response.data.meta.last_page
                            this.loading = false
                            this.updateUsersIdsAndNames()
                        })  
                        .catch(err => {
                            this.loading = true
                            console.error(err)
                        })
                })
                .catch(err => console.error(err))
        },

        updateUsersIdsAndNames() {
            this.usersIds = []
            this.usersNames = []
            this.users.map(user => {
                if (this.currentAuthorizedUser.id != user.id) {
                    this.usersIds.push(parseInt(user.id))
                    this.usersNames.push(user.name)
                }
            })
        },

        selectItem(checkbox, name) {
            const value = parseInt(checkbox.value)
            if (checkbox.checked) {
                this.checked.push(value) 
                this.selectedUsersNames.push(name)
                localStorage.setItem(`user-${value}`, name)
            } else {
                let index = this.checked.indexOf(value)
                this.checked.splice(index, 1)
                index = this.selectedUsersNames.indexOf(name)
                this.selectedUsersNames.splice(index, 1)
                localStorage.removeItem(`user-${value}`)
            }
        },

        selectAll(checkbox) {
            if (checkbox.checked) {
                this.users.map(user => {
                    if (this.currentAuthorizedUser.id != user.id) {
                        this.checked.push(parseInt(user.id))
                        this.selectedUsersNames.push(user.name)
                        localStorage.setItem(`user-${user.id}`, user.name)
                    }
                })
                this.getUniqueCheckedValues()
            } else {
                this.usersIds.map(id => {
                    let index = this.checked.indexOf(id)
                    this.checked.splice(index, 1)
                    Object.keys(localStorage).map(key => {
                        if (key.includes(`user-${id}`)) {
                            localStorage.removeItem(key)
                        }                
                    })
                })

                this.usersNames.map(name => {
                    let index = this.selectedUsersNames.indexOf(name)
                    this.selectedUsersNames.splice(index, 1)
                })

                
            }
        },

        checkIfSelectAll() {
            if (this.users != '') {
                return this.usersIds.every(id => this.checked.includes(id))
            }
        },

        searchFunction(event) {
            this.loading = true
            loadUsers({q: this.searchText})
                .then(response => {
                    this.users = response.data.data
                    this.totalPages = response.data.meta.last_page
                    this.page = response.data.meta.current_page
                    this.loading = false
                    this.updateUsersIdsAndNames()
                })  
                .catch(err => {
                    console.error(err)
                    this.loading = true
                })
        },

        getUniqueCheckedValues() {
            const unique = (value, index, self) => {
                return self.indexOf(value) === index
            }

            let uniq = this.checked.filter(unique)
            this.checked = uniq
            uniq = this.selectedUsersNames.filter(unique)
            this.selectedUsersNames = uniq
        }
    }
}
</script>

<style scoped>

    .d-flex {
        display: flex;
    }

    .checkAll {
        text-align: left;
        padding-left: 50px;
    }

    .btn {
        font-size: 18px;
        padding: 5px;
    }

    .checkbox {
        width: 20px;
        height: 20px;
    }

    .center {
        font-size: 72px;
        font-weight: 600;
        margin-top: 250px;
    }

    .fa-edit {
        margin-left: 15px;
        color: rgb(15, 144, 196);
        transition: .5s all;
    }

    .fa-edit:hover {
        font-size: 18px;
        color: rgb(11, 97, 131);
    }

    .fa-trash-alt {
        margin-left: 10px;
        color: rgb(170, 27, 27);
        transition: .5s all;
    }

    .fa-trash-alt:hover {
        cursor:pointer;
        font-size: 18px;
        color: rgb(126, 22, 22);
    }

</style>
