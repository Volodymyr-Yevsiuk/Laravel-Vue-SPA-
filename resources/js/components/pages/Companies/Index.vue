<template>
    <div>
        <div class="d-flex">
            <div class="d-flex content_block mx-auto content-center">
                <search v-model="searchText" @search="searchFunction"/>
                <vs-button class="create-button mx-auto" @click="toCreate">Зареєструвати компанію</vs-button>
                <spinner v-if="loading" style="margin-top: 100px;"/>
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
            mainText="Ви дійсно хочете видалити компанію:"  
            :deleteFunc="deleteCompany"  
            :id="companyIdForDelete"
            :items="companyNameForDelete"
            @cancel="cancelModal"
        />
    </div>  
</template>

<script>
import CompanyCard from '../../elements/CompanyCard.vue'
import DeleteModal from '../../elements/DeleteModal.vue'
import Spinner from '../../elements/Spinner.vue'
import {loadCompanies} from '../../../api/companies'
import {destroyCompany} from '../../../api/companies'
import store from '../../../store/index'
import Search from '../../elements/Search.vue'

export default {
    components: {
        CompanyCard,
        DeleteModal,
        Spinner,
        Search
    },
    data () {
        return {
            companies: {},
            page: 1,
            totalPages: 0,
            companyIdForDelete: null,
            companyNameForDelete: '',
            openModal: false,
            loading: false,
            searchText: ''
        }
    },
    async beforeRouteEnter(to, from, next) {
        await next(vm => {
            vm.loading = true
            loadCompanies()
                .then((response) => {
                    vm.companies = response.data.data
                    vm.totalPages = response.data.meta.last_page ? response.data.meta.last_page : 0
                    vm.page = response.data.meta.current_page
                    vm.loading = false
                })
                .catch(err => {
                    console.error(err)
                    vm.loading = true
                })
        })
    },
    methods: {
        changePage(page = 1) {
            this.loading = true
            loadCompanies ({page : page, q: this.searchText})
                .then((response) => {
                    this.companies = response.data.data
                    this.totalPages = response.data.meta.last_page
                    this.page = response.data.meta.current_page
                    this.loading = false
                })
                .catch(err => {
                    console.error(err)
                    this.loading = true
                })
        },

        show(id, target) {
            if (target && !(target.classList.contains('fa-edit') || target.classList.contains('fa-trash-alt'))) {
                this.$router.push({name: 'companies.show', params: { id: id }})
            }        
        },

        toCreate() {
            this.$router.push({name: 'companies.create'})
        },

        showModal(id, name) {
            this.companyIdForDelete = id
            this.companyNameForDelete = name
            this.openModal = true
        },

        cancelModal() {
            this.companyIdForDelete = null
            this.companyNameForDelete = ''
            this.openModal = false
        },

        async deleteCompany(id) {
            await destroyCompany(id)
                .then( response => {
                    store.commit('deleteAuthUserCompany', response.data.data)
                    this.loading = true
                    loadCompanies({q: this.searchText})
                        .then(response => {
                            this.companies = response.data.data
                            this.totalPages = response.data.meta.last_page
                            this.page = response.data.meta.current_page
                            this.loading = false
                        })  
                        .catch(err => {
                            console.error(err)
                            this.loading = true
                        })
                    console.log(`Компанію з id ${id} видалено`)
                })
                .catch(err => {
                    console.error(err)
                })
        },

        searchFunction(event) {
            this.loading = true
            loadCompanies({q: this.searchText})
                .then(response => {
                    this.companies = response.data.data
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
    .content_block {
        max-width: 900px;
        margin-top: 75px;
    }

    .create-button {
        font-size: 18px;
        padding: 5px;
        position: relative;
        margin: 0 auto;
        margin-bottom: 50px;
    }
</style>