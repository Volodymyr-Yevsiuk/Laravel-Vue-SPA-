<template>
    <div>
        <Menu/>
        <router-view></router-view>
    </div>
</template>

<script>
import Menu from './elements/Menu'

export default {
    components: { Menu },
    name: 'App',
}
</script>