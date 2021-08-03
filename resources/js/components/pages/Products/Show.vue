<template>
    <div>
        <div class="show-content mx-auto">
            <spinner v-if="loading"/>
            <img :src="`/images/${product.image}`" class="product-img">
            <div class="product-block">
                <label for="name">Назва:</label>
                <span id="name">{{product.name}}</span>
            </div>
            <div class="product-block">
                <label for="price">Ціна:</label>
                <span id="price">{{product.price}} грн</span>
            </div>
            <div class="product-block">
                <label for="company">Виробник:</label>
                <router-link :to="{name: 'companies.show', params: {id: company.id}}" id="company">{{company.name}}</router-link>
            </div>
            <div class="product-block">
                <label for="description">Опис:</label>
                <span id="description">{{product.description}}</span>
            </div>
            <div class="d-flex" v-if="showEditDestroyIcons()">
                <vs-button class="btn" @click="$router.push({name: 'products.edit', params: {id: product.id}})">Редагувати</vs-button>
                <vs-button class="btn" danger @click="showModal()">Видалити</vs-button>
            </div>
            <div class="comeback" @click="$router.go(-1)">
                Повернутися назад
                <i class="fas fa-long-arrow-alt-right"></i>
            </div>
            <delete-modal
                v-if="openModal"
                title="Видалення продукту"
                mainText="Ви дійсно хочете видалити цей продукт?"  
                :deleteFunc="deleteProduct"  
                :id="product.id"
                @cancel="cancelModal"
            />
        </div>
    </div>
</template>

<script>
import {loadProduct, destroyProduct} from '../../../api/products'
import Spinner from '../../elements/Spinner.vue'
import DeleteModal from '../../elements/DeleteModal.vue'

export default {
    components: { 
        Spinner,
        DeleteModal
    },
    data() {
        return {
            product: {},
            company: {},
            loading: false,
            openModal: false,
            isUserCompanyProduct: null,
            companiesIds: []
        }
    },
    async beforeRouteEnter (to, from, next) {
        await next(vm => {
            vm.loading = true
            
            loadProduct(to.params.id)
                .then(response => {
                    vm.product = response.data.data
                    vm.company = response.data.data.company
                    vm.loading = false
                    if (vm.currentAuthorizedUser != null && vm.currentAuthorizedUser.company != '') {
                        if (Object.keys(vm.currentAuthorizedUser.company).length > 1) {
                            for (let i in vm.currentAuthorizedUser.company) {
                                vm.companiesIds.push(vm.currentAuthorizedUser.company[i].id)
                            }

                            if (vm.product.company.id != null) {
                                vm.isUserCompanyProduct = vm.companiesIds.includes(vm.product.company.id) ? true : false
                            } else {
                                vm.isUserCompanyProduct = vm.companiesIds.includes(vm.product.company_id) ? true : false
                            }
                        } 
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
        await loadProduct(to.params.id)
            .then(response => {
                    this.product = response.data.data
                    this.company = response.data.data.company
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

        async deleteProduct(id) {
            await destroyProduct(id)
                .then( response => {
                    this.$router.go(-1)
                    console.log(`Продукт було ${id} видалено`)
                })
                .catch(err => {
                    console.error(err)
                })
        },

        showEditDestroyIcons () {
            if (this.currentAuthorizedUser != null) {
                if (this.currentAuthorizedUser.company != '') {
                    if (this.isUserCompanyProduct) {
                        return true
                    } else if (this.currentAuthorizedUser.company[0].id === (this.product.company.id || this.product.company_id)) {
                        return true
                    }  
                }
            } 
            return false
        }
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

    #company {
        color: rgb(0, 158, 179);
        text-decoration: none;
        transition: all 1s;
    }

    #company:hover {
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