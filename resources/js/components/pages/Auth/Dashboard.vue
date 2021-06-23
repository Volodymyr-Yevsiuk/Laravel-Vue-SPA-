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
                    <label for="role">Компанії, якими ви володієте:</label>
                    <span id="role">{{ user.company ? user.company.name : 'Ви не володієте жодними компаніями.' }}</span>
                    <router-link v-if="user.company == null" :to="{name: ''}">Зареєструвати свою компанію</router-link>
                </div>
                <div class="content-block">
                    <label for="created_at">Дата створення акаунта:</label>
                    <span id="created_at">{{ user.created_at }}</span>
                </div>
            </div>
        </div>
    </div>
</template>

<script>
export default {
    data () {
        return {
            user: null,
            photo: null,
            role: null
        }
    },
    mounted() {
        this.user = this.currentAuthorizedUser
        this.photo = 'https://www.gravatar.com/avatar/205e460b479e2e5b48aec07710c08d50?f=y&s=100'
        this.role = this.user.role
    }
}
</script>

<style scoped>
    .profile-block {
        width: 900px;
        height: 400px;
        margin: 0 auto;
        border: .5px solid rgb(201, 200, 200);
        margin-top: 50px;
        padding: 50px;
    }

    .profile-photo {
        border-radius: 50%;
    }

    .content-block {
        border-bottom: .5px solid rgb(124, 124, 124);
        margin-bottom: 15px;
    }
</style>