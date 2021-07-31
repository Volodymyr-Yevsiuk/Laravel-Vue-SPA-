<template>
    <div>
        <AdminMenu v-if="isAdmin || $route.name.includes('admin')"/>
        <Menu v-else/>
        <router-view></router-view>
    </div>
</template>

<script>
import Menu from './elements/Menu'
import AdminMenu from './elements/AdminMenu.vue'

export default {
    components: { Menu, AdminMenu },
    name: 'App',
}
</script>