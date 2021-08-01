<template>
    <div>
        <div class="show-content mx-auto">
            <spinner v-if="loading"/>
            <img :src="user.profile_photo_path" class="product-img">
            <div class="product-block">
                <label for="name">Ім'я:</label>
                <span id="name">{{user.name}}</span>
            </div>
            <div class="product-block">
                <label for="email">Електронна пошта:</label>
                <span id="email">{{user.email}}</span>
            </div>
            <div class="product-block">
                <label for="role">Роль:</label>
                <span id="role">{{ user.role.name }}</span>
            </div>
            <div class="product-block">
                <label for="created_at">Дата реєстрації:</label>
                <span id="created_at">{{user.created_at}}</span>
            </div>
            <div class="product-block">
                <label for="company">Компанії, якими володіє користувач:</label>
                <span 
                        v-if="(Object.keys(user.company).length <= 0)" 
                        id="company"
                    >
                        Користувач не володіє жодними компаніями.
                    </span>
                    <router-link 
                        v-if="(Object.keys(user.company).length === 1)"
                        :to="{name: 'companies.show', params: { id: user.company[Object.keys(user.company)[0]].id }}" 
                        class="company-link"
                    >
                        {{ user.company[Object.keys(user.company)[0]].name }}
                    </router-link>
                    <span 
                        v-else 
                        v-for="company in user.company" 
                        :key="company.id"
                        id="company"  
                    >
                        <router-link :to="{name: 'companies.show', params: { id: company.id }}" class="company-link">{{ company.name }}</router-link>,
                    </span>
            </div>
             <div class="d-flex">
                <vs-button class="btn" danger @click="showModal(user.id)">Видалити</vs-button>
            </div>
            <div class="comeback" @click="$router.go(-1)">
                Повернутися назад
                <i class="fas fa-long-arrow-alt-right"></i>
            </div>
            <delete-modal
                v-if="openModal"
                title="Видалення користувача"
                mainText="Ви дійсно хочете видалити цього користувача?"  
                :deleteFunc="deleteUser"  
                :id="user.id.toString()"
                @cancel="cancelModal"
            />
        </div>
    </div>
</template>

<script>
import moment from 'moment'
import {loadUser, destroyUser} from '../../../../api/users'
import Spinner from '../../../elements/Spinner.vue'
import DeleteModal from '../../../elements/DeleteModal.vue'

export default {
    components: {
        Spinner,
        DeleteModal
    },
    data() {
        return {
            user: {},
            prevRoute: '',
            loading: false,
            openModal: false
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
            loadUser(to.params.id)
                .then(response => {
                    vm.user = response.data.data

                    //datetime
                    moment.locale('uk')
                    vm.user.created_at = moment(vm.user.created_at).format('L')

                    vm.prevRoute = from.path
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
        await loadUser(to.params.id)
            .then(response => {
                    this.user = response.data.data

                    //datetime
                    moment.locale('uk')
                    this.user.created_at = moment(this.user.created_at).format('L')

                    this.prevRoute = from.path
                    this.loading = false
                })
            .catch(err => {
                console.error(err)
                this.loading = true
            })
        next()
    },
    methods: {
        showModal(id) {
            this.openModal = true
        },

        cancelModal() {
            this.openModal = false
        },

        async deleteUser(id) {
            await destroyUser(id)
                .then( response => {
                    this.$router.go(-1)
                    console.log(`Користувача з ${id} було видалено`)
                })
                .catch(err => {
                    console.error(err)
                })
        },
    }
}
</script>

<style scoped>

    .d-flex {
        display: flex;
    }

    .btn {
        font-size: 18px;
        padding: 5px;
    }

    .comeback {
        margin-top: 20px;
    }

    .comeback:hover {
        cursor: pointer;
        text-decoration: underline;
    }

    .show-content {
        width: 900px;
        margin-top: 100px;
        font-size: 18px;
        color: rgb(68, 66, 66);
        padding: 50px;
        border: .5px solid rgb(156, 153, 153);
        margin-bottom: 100px;
    }

    label {
        font-weight: 600;
    }

    .company-link {
        color: rgb(0, 158, 179);
        text-decoration: none;
        transition: all 1s;
    }

    .company-link:hover {
        color: rgb(1, 92, 104);
        text-decoration: underline;
    }

    .product-block {
        margin-bottom: 20px;;
    }

    .fa-long-arrow-alt-right {
        font-size: 25px;
    }

    .product-img {
        margin-bottom: 40px;
    }
</style>