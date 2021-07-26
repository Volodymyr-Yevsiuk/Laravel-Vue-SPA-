<template>
    <div class="dropdown">
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
</template>

<script>
export default {
    name: 'ProfileDropdownMenu'
}
</script>

<style scoped>
    .auth-user {
        display: flex;
        width: 270px;
        height: 50px;
        padding: 20px 5px;
        border: .5px solid rgb(201, 199, 199);
        border-radius: 10px;
    }

    button {
        background: #fff;
    }

    .photo {
        border-radius: 50%;
        margin-right: 15px;
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
        z-index: 5;
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