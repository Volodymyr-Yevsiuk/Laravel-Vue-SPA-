<template>
    <div>
        <div class="show-content mx-auto">
            <spinner v-if="loading"/>
            <img :src="`/images/${company.image}`" class="company-img">
            <div class="company-block">
                <label for="name">Назва:</label>
                <span id="name">{{company.name}}</span>
            </div>
            <div class="company-block">
                <label for="address">Адреса:</label>
                <span id="address">{{company.address}}</span>
            </div>
            <div class="company-block">
                <label for="price">К-сть співробіників:</label>
                <span id="price">Понад {{company.employees}} працівників</span>
            </div>
            <div class="company-block">
                <label for="user">Засновник:</label>
                <span id="user">{{user.name}}</span>
            </div>
            <div class="company-block">
                <label for="description">Опис:</label>
                <span id="description">{{company.description}}</span>
            </div>
            <div class="d-flex" v-if="isUserCompany === true">
                <vs-button class="btn" @click="$router.push({name: 'companies.edit', params: {id: company.id}})">Редагувати</vs-button>
                <vs-button class="btn" danger @click="showModal">Видалити</vs-button>
            </div>
            <div class="comeback" @click="$router.go(-1)">
                Повернутися назад
                <i class="fas fa-long-arrow-alt-right"></i>
            </div>
            <delete-modal
                v-if="openModal"
                title="Видалення компанії"
                mainText="Ви дійсно хочете видалити компанію:"  
                :deleteFunc="deleteCompany"
                :items="company.name"  
                :id="company.id"
                @cancel="cancelModal"
            />
        </div>
    </div>
</template>

<script>
import {loadCompany, destroyCompany} from '../../../api/companies'
import Spinner from '../../elements/Spinner.vue'
import DeleteModal from '../../elements/DeleteModal.vue'
import store from '../../../store/index'

export default {
    components: { 
        Spinner, 
        DeleteModal 
    },
    data() {
        return {
            company: {},
            user: {},
            loading: false,
            openModal: false,
            isUserCompany: null
        }
    },
    async beforeRouteEnter (to, from, next) {
        await next(vm => {
            vm.loading = true
            loadCompany(to.params.id)
                .then(response => {
                    vm.company = response.data.data
                    vm.user = response.data.data.user
                    vm.loading = false
                    if (vm.currentAuthorizedUser !== null) {
                        vm.isUserCompany = vm.company.user.id === vm.currentAuthorizedUser.id ? true : false
                    }
                })
                .catch(err => {
                    console.error(err)
                    vm.loading = true
                })
            
        }) 
    },
    async beforeRouteUpdate (to, from, next) {
        this.loading = true
        await loadCompany(to.params.id)
            .then(response => {
                    this.company = response.data.data
                    this.user = response.data.data.user
                    this.loading = false
                })
            .catch(err => {
                console.error(err)
                this.loading = true
            })
        next()
    },
    methods: {
        showModal() {
            this.openModal = true
        },

        cancelModal() {
            this.openModal = false
        },

        async deleteCompany(id) {
            await destroyCompany(id)
                .then( response => {
                    store.commit('deleteAuthUserCompany', response.data.data)
                    this.$router.go(-1)
                    console.log(`Компанію з id ${id} видалено`)
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

    .company-block {
        margin-bottom: 20px;;
    }

    .fa-long-arrow-alt-right {
        font-size: 25px;
    }

    .company-img {
        margin-bottom: 40px;
    }
</style>