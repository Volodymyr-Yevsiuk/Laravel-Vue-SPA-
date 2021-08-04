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
                        <input type="checkbox" name="ids" class="checkbox" :checked="checked.includes(user.id)" :value="user.id" @change="selectItem($event.target)"/>
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
                        <i class="fas fa-trash-alt" @click="showModal(user.id)"></i>
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
            mainText="Ви дійсно хочете видалити цього користувача?"  
            :deleteFunc="deleteUser"  
            :id="userIdForDelete"
            @cancel="cancelModal"
        />
        <delete-modal
            v-if="openModal && (typeof userIdForDelete) == 'object'"
            title="Видалення користувачів"
            mainText="Ви дійсно хочете видалити вибраних користувачів?"  
            :deleteFunc="deleteUsers"  
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
            page: 1,
            totalPages: 0,
            openModal: false,
            userIdForDelete: null,
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
            })
            .catch(err => {
                console.error(err)
                vm.loading = true
            })
        })
    },
    async beforeRouteUpdate (to, from, next) {
        this.loading = true
        await loadUsers()
            .then(response => {
                this.users = response.data.data
                this.page = response.data.meta.current_page
                this.totalPages = response.data.meta.last_page
                this.loading = false
            })
            .catch(err => {
                console.error(err)
                this.loading = true
            })
            next()
    },
    methods: {
        async changePage(page = 1) {
            this.loading = true
            await loadUsers ({page : page})
                .then((response) => {
                    this.users = response.data.data
                    this.totalPages = response.data.meta.last_page
                    this.page = response.data.meta.current_page
                    this.loading = false
                })
                .catch(err => {
                    console.error(err)
                    this.loading = true
                })
        },
        showModal(id) {
            this.userIdForDelete = id
            this.openModal = true
        },

        cancelModal() {
            this.userIdForDelete = null
            this.openModal = false
        },

        async deleteUser(id) {
            await destroyUser(id)
                .then( response => {
                    this.loading = true
                    if (this.checked.includes(id)) {
                        console.log(id)
                        let index = this.checked.indexOf(id)
                        this.checked.splice(index, 1)
                    }
                    loadUsers()
                        .then(response => {
                            this.users = response.data.data
                            this.totalPages = response.data.meta.last_page
                            this.page = response.data.meta.current_page
                            this.loading = false
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
                    loadUsers()
                        .then(response => {
                            this.users = response.data.data
                            this.page = response.data.meta.current_page
                            this.totalPages = response.data.meta.last_page
                            this.loading = false
                        })  
                        .catch(err => {
                            this.loading = true
                            console.error(err)
                        })
                })
                .catch(err => console.error(err))
        },

        selectItem(checkbox) {
            const value = parseInt(checkbox.value)
            if (checkbox.checked) {
                this.checked.push(value) 
            } else {
                let index = this.checked.indexOf(value)
                this.checked.splice(index, 1)
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
                })  
                .catch(err => {
                    console.error(err)
                    this.loading = true
                })
        } 
    }
}
</script>

<style scoped>

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
