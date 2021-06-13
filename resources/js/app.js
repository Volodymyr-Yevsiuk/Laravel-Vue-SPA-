import Vue from 'vue'
import VueRouter from 'vue-router'
import VueSax from 'vuesax'
import App from './components/App' 
import router from './routes'
import 'vuesax/dist/vuesax.css'

require('./bootstrap');

Vue.use(VueRouter)
Vue.use(VueSax)

new Vue({
    render: h => h(App),
    router
}).$mount('#app')

