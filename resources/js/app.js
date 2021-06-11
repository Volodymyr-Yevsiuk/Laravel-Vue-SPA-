import Vue from 'vue'
import VueRouter from 'vue-router'
import App from './components/App' 
import router from './routes'

require('./bootstrap');

Vue.use(VueRouter)

new Vue({
    render: h => h(App),
    router
}).$mount('#app')

