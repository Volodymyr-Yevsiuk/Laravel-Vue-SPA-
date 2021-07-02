<template>
    <div>
        <div class="show-content mx-auto">
            <img :src="`/images/${company.image}`" class="company-img">
            <div class="d-flex company-block">
                <label for="name">Назва:</label>
                <span id="name">{{company.name}}</span>
            </div>
            <div class="d-flex company-block">
                <label for="user">Засновник:</label>
                <router-link :to="{name: 'users.show', params: {id: user.id}}" id="user">{{user.name}}</router-link>
            </div>
            <div class="d-flex company-block">
                <label for="price">К-сть співробіників:</label>
                <span id="price">Понад {{company.employees}} працівників</span>
            </div>
            <div class="d-flex company-block">
                <label for="description">Опис:</label>
                <span id="description">{{company.description}}</span>
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
import {loadCompany} from '../../../api/companies'

export default {
    data() {
        return {
            company: {},
            user: {},
            prevRoute: ''
        }
    },
    async beforeRouteEnter (to, from, next) {
        await next(vm => {
            loadCompany(to.params.id)
                .then(response => {
                    vm.company = response.data.data
                    vm.user = response.data.data.user
                    vm.prevRoute = from.path
                })
                .catch(err => console.error(err))
        }) 
    },
    async beforeRouteUpdate (to, from, next) {
        await loadCompany(to.params.id)
            .then(response => {
                    this.company = response.data.data
                    this.user = response.data.data.user
                    this.prevRoute = from.path
                })
            .catch(err => console.error(err))
        next()
    },

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

    #user {
        color: rgb(0, 158, 179);
        text-decoration: none;
        transition: all 1s;
    }

    #user:hover {
        color: rgb(1, 92, 104);
        text-decoration: underline;
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