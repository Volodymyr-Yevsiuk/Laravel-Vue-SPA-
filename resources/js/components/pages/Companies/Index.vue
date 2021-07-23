<template>
    <div>
        <div class="d-flex">
            <div class="d-flex content_block mx-auto content-center">
                <vs-button class="create-button mx-auto" @click="toCreate">Зареєструвати компанію</vs-button>
                <company-card 
                    v-for="company in companies" 
                    :key="company.id"
                    :company="company"
                    @delete="showModal"
                    @view="show"
                />
            </div>
        </div>
        <vs-pagination v-if="totalPages > 1" v-model="page" :length="totalPages" @input="changePage" />
        <delete-modal
            v-if="openModal"
            title="Видалення компанії"
            mainText="Ви дійсно хочете видалити компанію?"  
            :deleteFunc="deleteCompany"  
            :id="companyIdForDelete"
            @cancel="cancelModal"
        />
    </div>  
</template>

<script>
import CompanyCard from '../../elements/CompanyCard.vue'
import DeleteModal from '../../elements/DeleteModal.vue'
import {loadCompanies} from '../../../api/companies'
import {destroyCompany} from '../../../api/companies'
import store from '../../../store/index'

export default {
    components: {
        CompanyCard,
        DeleteModal
    },
    data () {
        return {
            companies: {},
            page: 1,
            totalPages: 0,
            companyIdForDelete: null,
            openModal: false
        }
    },
    async beforeRouteEnter(to, from, next) {
        await next(vm => {
            loadCompanies()
                .then((response) => {
                    vm.companies = response.data.data
                    vm.totalPages = response.data.meta.last_page ? response.data.meta.last_page : 0
                    vm.page = response.data.meta.current_page
                })
                .catch((err) => console.error(err))
        })
    },
    async beforeRouteUpdate(to, from, next) {
        loadCompanies()
            .then((response) => {
                    this.companies = response.data.data
                    this.totalPages = response.data.meta.last_page ? response.data.meta.last_page : 0
                    this.page = response.data.meta.current_page
                })
            .catch((err) => console.error(err))
        next()
    },
    methods: {
        changePage(page = 1) {
            loadCompanies (page)
                .then((response) => {
                    this.companies = response.data.data
                    this.totalPages = response.data.meta.last_page
                    this.page = response.data.meta.current_page
                })
                .catch((err) => console.error(err))
        },

        show(id, target) {
            if (target && !(target.classList.contains('fa-edit') || target.classList.contains('fa-trash-alt'))) {
                this.$router.push({name: 'companies.show', params: { id: id }})
            }        
        },

        toCreate() {
            this.$router.push({name: 'companies.create'})
        },

         showModal(id) {
            this.companyIdForDelete = id
            this.openModal = true
        },

        cancelModal() {
            this.companyIdForDelete = null
            this.openModal = false
        },

        async deleteCompany(id) {
            await destroyCompany(id)
                .then( response => {
                    store.commit('deleteAuthUserCompany', response.data.data)
                    loadCompanies()
                        .then(response => {
                            this.companies = response.data.data
                        })  
                        .catch(err => console.error(err))
                    console.log(`Компанію з id ${id} видалено`)
                })
                .catch(err => console.error(err))
        }
    }
}
</script>

<style scoped>
    .content_block {
        max-width: 900px;
        margin-top: 75px;
    }

    .create-button {
        font-size: 18px;
        padding: 5px;
        position: relative;
        left: 38%;
        margin-bottom: 50px;
    }
</style>