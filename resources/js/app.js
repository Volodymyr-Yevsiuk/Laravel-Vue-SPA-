import Vue from 'vue'
import VueRouter from 'vue-router'
import VueSax from 'vuesax'
import App from './components/App' 
import router from './routes'
import mixins from './global/mixins'
import { mixin as clickaway } from 'vue-clickaway';
import 'vuesax/dist/vuesax.css'

require('./bootstrap');

Vue.use(VueRouter)
Vue.use(VueSax)
Vue.mixin(mixins)

new Vue({
    render: h => h(App),
    router,
    mixins: [clickaway]
}).$mount('#app')

