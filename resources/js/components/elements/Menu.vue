<template>
    <div class="navbar mx-auto font">
        <div class="not_auth mx-auto">
            <router-link :to="{name: 'home'}">Головна</router-link>
            <router-link :to="{name: 'products.index'}">Продукти</router-link>
            <router-link to="/companies">Компанії</router-link>
            <div v-if="!currentAuthorizedUser">
                <a href="/login" class="mr-2">Увійти</a>
                <a href="/register">Зареєструватися</a>
            </div>  
            <div v-else class=" auth-user">
                <a href="/dashboard" class="">
                    <img v-if="!currentAuthorizedUser.profile_photo_path" :src="currentAuthorizedUser.profile_photo_path" class="photo"/>
                    <img v-else src="https://www.gravatar.com/avatar/205e460b479e2e5b48aec07710c08d50?f=y&s=35" class="photo"/>
                </a> 
                <a href="/dashboard" class="name-mb">{{ currentAuthorizedUser.name }}</a>
            </div>
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

    .photo {
        border-radius: 50%;
    }

    .auth-user {
        width: 270px;
        padding: 5px;
        padding-bottom: 20px;
    }

    .name-mb {
        display: inline-block;
    }
</style>