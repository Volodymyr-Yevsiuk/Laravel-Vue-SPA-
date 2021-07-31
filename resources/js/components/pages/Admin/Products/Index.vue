<template>
    <div class="text-center">
        <search v-model="searchText" @search="searchFunction"/>
        <vs-table>
            <template #thead>
                <vs-tr>
                    <vs-th>Фото</vs-th>
                    <vs-th>Назва</vs-th>
                    <vs-th>Ціна</vs-th>
                    <vs-th>Власник</vs-th>
                    <vs-th>Переглянути</vs-th>
                    <vs-th>Редагувати</vs-th>
                    <vs-th>Видалити</vs-th>
                </vs-tr>
            </template>
            <template #tbody>
                <spinner v-if="loading"/>
                <vs-tr
                    v-for="(product, i) in products"
                    :key="i"
                    :data="product"
                >
                    <vs-td>
                        <img :src="`/images/${product.image}`"/> 
                    </vs-td>
                    <vs-td>
                        {{ product.name }}
                    </vs-td>
                    <vs-td>
                        {{ product.price }}
                    </vs-td>
                    <vs-td>
                        {{ product.company.name }}
                    </vs-td>
                    <vs-td>
                        <router-link :to="{name: 'products.show', params: { id: product.id} }">
                            <i class="fas fa-eye"></i>
                        </router-link>
                    </vs-td>
                    <vs-td>
                        <router-link :to="{name: 'products.edit', params: { id: product.id} }">
                            <i class="fas fa-edit"></i>
                        </router-link>
                    </vs-td>
                    <vs-td>
                        <i class="fas fa-trash-alt" @click="showModal(product)"></i>
                    </vs-td>
                </vs-tr>
            </template>
            <template #footer>
                <vs-pagination v-if="totalPages > 1" v-model="page" :length="totalPages" @input="changePage" />
            </template>
      </vs-table>
      <delete-modal
            v-if="openModal"
            title="Видалення продукта"
            mainText="Ви дійсно хочете видалити цей продукт?"  
            :deleteFunc="deleteProduct"  
            :id="productIdForDelete"
            @cancel="cancelModal"
        />
    </div>
</template>

<script>
import {loadProducts} from '../../../../api/products'
import {destroyProduct} from '../../../../api/products'
import DeleteModal from '../../../elements/DeleteModal.vue'
import Search from '../../../elements/Search.vue'
import Spinner from '../../../elements/Spinner.vue'


export default {
    components: {
        DeleteModal,
        Spinner,
        Search
    },
    data() {
        return {
            products: [],
            page: 1,
            totalPages: 0,
            openModal: false,
            productIdForDelete: null,
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
            loadProducts()
            .then(response => {
                vm.products = response.data.data
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
        await loadProducts()
            .then(response => {
                this.products = response.data.data
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
            await loadProducts ({page: page})
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
        showModal(id) {
            this.productIdForDelete = id.toString()
            this.openModal = true
        },

        cancelModal() {
            this.productIdForDelete = null
            this.openModal = false
        },

        async deleteProduct(id) {
            await destroyProduct(id)
                .then( response => {
                    this.loading = true
                    loadProducts()
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
                    console.log(`Продукт з ${id} було видалено`)
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
