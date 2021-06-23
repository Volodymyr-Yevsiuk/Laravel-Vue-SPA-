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
            <div class="dropdown" v-else>
                <button class="d-flex content-center items-center auth-user">
                    <img v-if="currentAuthorizedUser.profile_photo_path != null" :src="currentAuthorizedUser.profile_photo_path" class="photo"/>
                    <img v-else src="https://www.gravatar.com/avatar/205e460b479e2e5b48aec07710c08d50?f=y&s=35" class="photo"/> 
                    <span class="name">{{ currentAuthorizedUser.name }}</span>
                    <i class="fas fa-angle-down"></i>
                </button>
                <div class="dropdown-content">
                    <div class="dropdown-block">
                        <i class="fas fa-user"></i>
                        <router-link :to="{name: 'dashboard'}" class="dropdown-link">Профіль</router-link>
                    </div>     
                    <div class="dropdown-block">
                        <i class="fas fa-sign-out-alt"></i>
                        <a href="/logout" class="dropdown-link" id="logout">Вийти</a>
                    </div>
                </div>
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
        margin-right: 15px;
    }

    button {
        background: #fff;
    }

    .auth-user {
        display: flex;
        width: 270px;
        height: 50px;
        padding: 20px 5px;
        border: .5px solid rgb(201, 199, 199);
        border-radius: 10px;
    }

    .name {
        font-size: 18px;
        color: rgb(102, 99, 99);
        font-weight: 600;
    }

    .dropdown-content {
        width: 270px;
        display: none;
        flex-direction: column;
        color: rgb(102, 99, 99);
        border: .5px solid rgb(201, 199, 199);
        border-radius: 10px;
        padding: 15px;
        font-size: 18px;
        background: #fff;
        position: relative;
        z-index: 2px;
    }

    .dropdown:hover .dropdown-content {
        display: flex;
    }

    .dropdown-link {
        display: flex;
        align-items: center;
        height: 30px; 
    }

    .dropdown-block:first-child{
        border-bottom: .5px solid rgb(201, 199, 199);
    }

    .fa-angle-down {
        font-size: 18px;
        color: rgb(102, 99, 99);
        margin-left: 15px;
    }

    .dropdown:hover .fa-angle-down {
        color: rgb(61, 59, 59);
    }

    .dropdown-block {
        display: flex;
        align-items: center;
        height: 30px;
    }

    .dropdown-block .fas {
        margin-right: 5px;
    }
</style>
