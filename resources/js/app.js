
require('./bootstrap');

window.Vue = require('vue');

import VueRouter from "vue-router";
import vuetify from "./src/plugins/vuetify";
import { routes } from "./routes";
import LeadApp from './pages/LeadApp'

Vue.use(VueRouter);


// Vue.component('example-component', require('./components/ExampleComponent.vue').default);


const router = new VueRouter({
    routes,
    mode: "history"
});

const app = new Vue({
    vuetify,
    router,
    el: '#app',
    components: {
        "lead-app": LeadApp,
    }
});
