<template>
    <div class="text-center">
        <span class="center">Хай адмін :)</span>
        <div class="comeback ">
            <vs-button @click="goOutFromAdmin" class="cumback">
                Вийти з адміністративної панелі <i class="fas fa-long-arrow-alt-right"></i>
            </vs-button>
        </div>
    </div>
</template>

<script>
import store from '../../../store/index'

export default {
    beforeRouteEnter(to, from, next) {
        next(vm => {
            if (vm.currentAuthorizedUser === null) {
                window.location.href = '/login'
            } else {
                if (vm.currentAuthorizedUser.role.name !== 'Admin') {
                    vm.$router.push({name: 'forbidden'})
                }
            }
        })
    },
    methods: {
        goOutFromAdmin() {
            store.commit('changeIsAdmin', false)
            this.$router.push({name: 'home'})
        }
    }
}
</script>

<style scoped>
    .center {
        font-size: 72px;
        font-weight: 600;
        margin-top: 250px;
    }

    .comeback {
        font-size: 24px;
        margin-top: 200px;
    }

    .cumback {
        padding: 10px;
        font-size: 24px;
        margin: 0 auto;
    }

    .fa-long-arrow-alt-right {
        font-size: 30px;
        margin-left: 10px;
    }
</style>
