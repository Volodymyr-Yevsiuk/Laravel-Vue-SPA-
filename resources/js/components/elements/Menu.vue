<template>
    <div class="navbar mx-auto font">
        <div class="not_auth mx-auto">
            <router-link to="/products">Продукти</router-link>
            <router-link to="/companies">Компанії</router-link>
            <router-link to="/login">Увійти</router-link>
            <router-link to="/register">Зареєструватися</router-link>
        </div>
        <div>
            <router-link to="/"></router-link>
        </div>
    </div>
</template>

<script>
export default {
    name: 'Menu'
}
</script>

<style scoped>
    .navbar {
        width: 800px;
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