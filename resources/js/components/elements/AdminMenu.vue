<template>
    <div class="navbar mx-auto font">
        <div class="not_auth mx-auto">
            <router-link :to="{name: 'admin.index'}">Головна</router-link>
            <router-link :to="{name: 'admin.products.index'}">Продукти</router-link>
            <router-link :to="{name: 'admin.companies.index'}">Компанії</router-link>
            <router-link :to="{name: 'admin.users.index'}">Користувачі</router-link>  
            
            <dropdown />
        </div>
    </div>
</template>

<script>
import Dropdown from '../elements/ProfileDropdown.vue'
export default {
  components: { Dropdown },
    name: 'Menu',
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
