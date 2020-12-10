import Vue from 'vue'
import VueRouter from 'vue-router'
import Vuetify from 'vuetify'
import BootstrapVue from 'bootstrap-vue'
import VueSweetalert2 from 'vue-sweetalert2'
import { Cropper } from 'vue-advanced-cropper'


import App from './app.vue'
import Routers from './router.js'
import store from './store.js'

import 'vuetify/dist/vuetify.min.css'
import 'bootstrap/dist/css/bootstrap.css'
import 'bootstrap-vue/dist/bootstrap-vue.css'

Vue.use(VueRouter)
Vue.use(Vuetify)
Vue.use(BootstrapVue)
Vue.use(VueSweetalert2)
Vue.use(Cropper)

// The routing configuration
const RouterConfig = {
    mode: 'history',
    routes: Routers
};
const router = new VueRouter(RouterConfig)

router.beforeEach((to, from, next) => {
    store.commit('CLEAR_ERRORS')
    if (to.matched.some(record => record.meta.requiresAuth)) {
        let auth = store.getters.isAuth
        if (!auth) {
            next({ name: 'login' })
        } else {
            next()
        }
    } else {
        next()
    }
})

new Vue({
    el: '#app',
    router: router,
    store: store,
    render: h => h(App)
});
