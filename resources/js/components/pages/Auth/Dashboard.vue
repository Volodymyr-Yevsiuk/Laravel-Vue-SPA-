<template>
    <div>
        <div class="profile-block">
            <div class="photo-name">
                <img :src="photo" class="profile-photo">
                <h3>{{ user.name }}</h3>
            </div>
            <div class="profile-other">
                <div class="content-block">
                    <label for="email">Електронна пошта:</label>
                    <span id="email">{{ user.email }}</span>
                </div>
                <div class="content-block">
                    <label for="role">Роль користувача:</label>
                    <span id="role">{{ role.name }}</span>
                </div>
                <div class="content-block">
                    <label for="company">Компанії, якими ви володієте:</label>
                    <span 
                        v-if="Object.keys(user.company).length <= 1" 
                        id="company"
                    >
                        {{ user.company ? user.company.name : 'Ви не володієте жодними компаніями.' }}
                    </span>
                    <span 
                        v-else
                        v-for="company in user.company" 
                        :key="company.id"
                        id="company"  
                    >
                        <router-link :to="{name: 'companies.show', params: { id: company.id }}" class="company-link">{{ company.name }}</router-link>,
                    </span>
                    <vs-button v-if="user.company == null" @click="registerCompany">Зареєструвати свою компанію</vs-button>
                </div>
                <div class="content-block">
                    <label for="created_at">Дата створення акаунта:</label>
                    <span id="created_at">{{ user.created_at }}</span>
                </div>
                <div class="content-block">
                    <vs-button class="logout">
                        <a href="/logout">Вийти з акаунта</a>
                    </vs-button>
                </div>
            </div>
        </div>
        <div v-if="user.company" class="products">
            <h1 class="products-title">Ваші товари</h1>
            <div>
                <vs-button class="create-button mx-auto" @click="toCreate">Створити продукт</vs-button>
                <product-card 
                    v-for="product in products" 
                    :key="product.id"
                    :product="product"
                    @view="show"
                    @delete="showModal"
                />
                <vs-pagination v-if="totalPages > 1" v-model="page" :length="totalPages" @input="changePage" />
            </div>
        </div>
        <delete-modal
            v-if="openModal"
            title="Видалення продукту"
            mainText="Ви дійсно хочете видалити цей продукт?"  
            :deleteFunc="deleteProduct"  
            :productId="productIdForDelete"
            @cancel="cancelModal"
        />
    </div>
</template>

<script>
import moment from 'moment'
import ProductCard from '../../elements/ProductCard.vue'
import {loadCompanyProducts} from '../../../api/companies'
import DeleteModal from '../../elements/DeleteModal.vue'
import {destroyProduct} from '../../../api/products'

export default {
    components: {
        ProductCard,
        DeleteModal
    },
    data () {
        return {
            user: null,
            photo: null,
            role: null,
            products: [],
            page: 1,
            totalPages: 0,
            openModal: false,
            productIdForDelete: null
        }
    },
    async beforeRouteEnter (to, from, next) {
        await next(vm => {
            if (vm.currentAuthorizedUser != null) {
                vm.user = vm.currentAuthorizedUser
                vm.photo = 'https://www.gravatar.com/avatar/205e460b479e2e5b48aec07710c08d50?f=y&s=100'
                vm.role = vm.currentAuthorizedUser.role

                // datetime
                moment.locale('uk')
                vm.user.created_at = moment(vm.user.created_at).format('L')

                let companyIds = vm.user.company.id;
                if (Object.keys(vm.user.company).length > 1) {
                    companyIds = []
                    for (let i in vm.user.company) {
                        companyIds.push(vm.user.company[i].id)
                    }
                }

                // load users products
                loadCompanyProducts(vm.user.id)
                .then(response => {
                    response.data.data.forEach(company => {
                        company.products.forEach(product => {
                            product['company'] = company.name
                            vm.products.push(product)
                        })
                    })
                })  
                .catch(err => console.error(err))
            } else {
                window.location.href = '/login'
            }
            
        })
    },
    methods: {
        registerCompany() {
            this.$router.push({name: 'companies.create'})
        },

        changePage(page = 1) {
            loadProducts (page)
                .then((response) => {
                    this.products = response.data.data
                    this.totalPages = response.data.meta.last_page
                    this.page = response.data.meta.current_page
                })
                .catch((err) => console.error(err))
        },

        show(id, target) {
            if (target && !(target.classList.contains('fa-edit') || target.classList.contains('fa-trash-alt'))) {
                this.$router.push({name: 'products.show', params: { id: id }})
            }
        },

        toCreate() {
            this.$router.push({name: 'products.create'})
        },

        showModal(id) {
            this.productIdForDelete = id
            this.openModal = true
        },

        cancelModal() {
            this.productIdForDelete = null
            this.openModal = false
        },

        async deleteProduct(id) {
            await destroyProduct(id)
                .then( response => {
                    loadCompanyProducts(this.user.id)
                        .then(response => {
                            this.products = []
                            response.data.data.forEach(company => {
                                company.products.forEach(product => {
                                    product['company'] = company.name
                                    this.products.push(product)
                                })
                            })
                        })  
                        .catch(err => console.error(err))
                    console.log(`Продукт було ${id} видалено`)
                })
                .catch(err => console.error(err))
        }

    }
}
</script>

<style scoped>
    .profile-block {
        width: 900px;
        height: 500px;
        margin: 0 auto;
        border: .5px solid rgb(201, 200, 200);
        margin-top: 50px;
        padding: 50px;
    }

    .create-button {
        font-size: 18px;
        padding: 5px;
        position: relative;
        left: 38%;
        margin-bottom: 50px;
    }

    .profile-photo {
        border-radius: 50%;
    }

    .content-block {
        border-bottom: .5px solid rgb(124, 124, 124);
        margin-bottom: 15px;
    }

    .content-block:last-child {
        border-bottom: 0;
    }

    .products {
        width: 900px;
        margin: 0 auto;
        margin-top: 50px;
    }

    .products-title {
        border-bottom: .5px solid;
        margin-bottom: 50px;
    }

    .logout {
        font-size: 16px;
        padding: 5px;
    }

    .company-link {
        color: rgb(24, 98, 168);
        transition: .5s all;
    }

    .company-link:hover {
        color: rgb(21, 80, 134);
        text-decoration: underline;
    }
</style>