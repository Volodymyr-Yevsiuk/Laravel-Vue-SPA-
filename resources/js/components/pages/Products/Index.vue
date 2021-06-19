<template>
    <div>
        <div class="d-flex">
            <div class="d-flex content_block mx-auto content-center">
                <vs-button class="create-button mx-auto" @click="toCreate">Створити продукт</vs-button>
                <product-card 
                    v-for="product in products" 
                    :key="product.id"
                    :product="product"
                    @view="show"
                />
            </div>
        </div>
        <vs-pagination v-if="totalPages > 1" v-model="page" :length="totalPages" @input="changePage" />
    </div>  
</template>

<script>
import ProductCard from '../../elements/ProductCard.vue'
import {loadProducts} from '../../../api/products'

export default {
    components: {
        ProductCard
    },
    data () {
        return {
            products: {},
            page: 1,
            totalPages: 0
        }
    },
    async beforeRouteEnter(to, from, next) {
        await next(vm => {
            loadProducts()
                .then((response) => {
                    vm.products = response.data.data
                    vm.totalPages = response.data.meta.last_page ? response.data.meta.last_page : 0
                    vm.page = response.data.meta.current_page
                })
                .catch((err) => console.error(err))
        })
    },
    async beforeRouteUpdate(to, from, next) {
        loadProducts()
            .then((response) => {
                    this.products = response.data.data
                    this.totalPages = response.data.meta.last_page ? response.data.meta.last_page : 0
                    this.page = response.data.meta.current_page
                })
            .catch((err) => console.error(err))
        next()
    },
    methods: {
        changePage(page = 1) {
            loadProducts (page)
                .then((response) => {
                    this.products = response.data.data
                    this.totalPages = response.data.meta.last_page
                    this.page = response.data.meta.current_page
                })
                .catch((err) => console.error(err))
        },

        show(id) {
            this.$router.push({name: 'products.show', params: { id: id }})
        },

        toCreate() {
            this.$router.push({name: 'products.create'})
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
        left: 38%;
        margin-bottom: 50px;
    }
</style>