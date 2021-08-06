<template>
    <div class="navbar mx-auto font">
        <div class="not_auth mx-auto">
                <router-link :to="{name: 'home'}">Головна</router-link>
                <router-link :to="{name: 'products.index'}">Продукти</router-link>
                <router-link :to="{name: 'companies.index'}">Компанії</router-link>
            <div v-if="!currentAuthorizedUser">
                <a href="/login" class="mr-2">Увійти</a>
                <a href="/register">Зареєструватися</a>
            </div>
            <dropdown v-else/> 
        </div>
    </div>
</template>

<script>
import Dropdown from '../elements/ProfileDropdown.vue'
export default {
    name: 'Menu',
    components: { Dropdown }
}
</script>

<style scoped>
    .navbar {
        width: 900px;
        height: 80px;
        padding-top: 10px;
        padding: 30px 30px 0 30px;
        border-bottom: .5px solid rgb(122, 119, 119);
    }

    .font {
        font-family: Arial, Helvetica, sans-serif;
        font-size: 22px;
        font-weight: 600;
        color: rgb(102, 99, 99);
    }

    .not_auth {
        display: flex;
        justify-content: space-between;
    }
</style>
