<template>
    <div>
        <div class="show-content mx-auto">
            <img :src="`/images/${product.image}`" class="product-img">
            <div class="d-flex product-block">
                <label for="name">Назва:</label>
                <span id="name">{{product.name}}</span>
            </div>
            <div class="d-flex product-block">
                <label for="price">Ціна:</label>
                <span id="price">{{product.price}} грн</span>
            </div>
            <div class="d-flex product-block">
                <label for="company">Виробник:</label>
                <router-link :to="{name: 'companies.show', params: {id: company.id}}" id="company">{{company.name}}</router-link>
            </div>
            <div class="d-flex product-block">
                <label for="description">Опис:</label>
                <span id="description">{{product.description}}</span>
            </div>
            <div>
                <router-link :to="{path: prevRoute}">
                    Повернутися назад
                    <i class="fas fa-long-arrow-alt-right"></i>
                </router-link>
            </div>
        </div>
    </div>
</template>

<script>
import {loadProduct} from '../../../api/products'

export default {
    data() {
        return {
            product: {},
            company: {},
            prevRoute: ''
        }
    },
    async beforeRouteEnter (to, from, next) {
        await next(vm => {
            loadProduct(to.params.id)
                .then(response => {
                    vm.product = response.data.data
                    vm.company = response.data.data.company
                    vm.prevRoute = from.path
                })
                .catch(err => console.error(err))
        }) 
    },
    async beforeRouteUpdate (to, from, next) {
        await loadProduct(to.params.id)
            .then(response => {
                    this.product = response.data.data
                    this.company = response.data.data.company
                    this.prevRoute = from.path
                })
            .catch(err => console.error(err))
        next()
    }
}
</script>

<style scoped>
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