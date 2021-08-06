<template>
    <div>
        <div class="d-flex">
            <div class="d-flex content_block mx-auto content-center">
                <search v-model="searchText" @search="searchFunction"/>
                <vs-button class="create-button mx-auto" @click="toCreate">Створити продукт</vs-button>
                <spinner v-if="loading"/>
                <product-card 
                    v-for="product in products" 
                    :key="product.id"
                    :product="product"
                    @view="show"
                    @delete="showModal"
                />
            </div>
        </div>
        <vs-pagination v-if="totalPages > 1" v-model="page" :length="totalPages" @input="changePage" />
        <delete-modal
            v-if="openModal"
            title="Видалення продукту"
            mainText="Ви дійсно хочете видалити цей продукт:"  
            :deleteFunc="deleteProduct"  
            :items="productNameForDelete"
            :id="productIdForDelete"
            @cancel="cancelModal"
        />
    </div>  
</template>

<script>
import ProductCard from '../../elements/ProductCard.vue'
import DeleteModal from '../../elements/DeleteModal.vue'
import Spinner from '../../elements/Spinner.vue'
import Search from '../../elements/Search.vue'
import {loadProducts} from '../../../api/products'
import {destroyProduct} from '../../../api/products'

export default {
    components: {
        ProductCard,
        DeleteModal,
        Spinner,
        Search
    },
    data () {
        return {
            products: {},
            page: 1,
            totalPages: 0,
            openModal: false,
            productIdForDelete: null,
            productNameForDelete: '',
            loading: false,
            searchText: ''
        }
    },
    async beforeRouteEnter(to, from, next) {
        await next(vm => {
            vm.loading = true
            loadProducts()
                .then((response) => {
                    vm.products = response.data.data
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
            loadProducts ({page : page, q: this.searchText})
                .then((response) => {
                    this.products = response.data.data
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
                this.$router.push({name: 'products.show', params: { id: id }})
            }        
        },

        toCreate() {
            this.$router.push({name: 'products.create'})
        },

        showModal(id, name) {
            this.productIdForDelete = id
            this.productNameForDelete = name
            this.openModal = true
        },

        cancelModal() {
            this.productIdForDelete = null
            this.productNameForDelete = ''
            this.openModal = false
        },

        async deleteProduct(id) {
            await destroyProduct(id)
                .then( response => {
                    this.loading = true
                    loadProducts({q: this.searchText})
                        .then(response => {
                            this.products = response.data.data
                            this.totalPages = response.data.meta.last_page
                            this.page = response.data.meta.current_page
                            this.loading = false
                        })  
                        .catch(err => {
                            console.error(err)
                            this.loading = true
                        })
                    console.log(`Продукт з id ${id} видалено`)
                })
                .catch(err => {
                    console.error(err)
                })
        },

        searchFunction(event) {
            this.loading = true
            loadProducts({q: this.searchText})
                .then(response => {
                    this.products = response.data.data
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
        width: 1200px;
        margin-top: 75px;
        padding-left: 70px;
    }

    .create-button {
        font-size: 18px;
        padding: 5px;
        position: relative;
        margin: 0 auto;
        margin-bottom: 50px;
    }
</style>