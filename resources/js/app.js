require('./bootstrap');

import Vue from "vue";
import VueRouter from "vue-router";
import DomainComponent from "./components/DomainComponent.vue";
import ExampleComponent from "./components/ExampleComponent.vue";

const routes = [
    { path: '/', component: DomainComponent },
    { path: '/test2', component: ExampleComponent }
];

const router = new VueRouter({
    routes
});

Vue.use(VueRouter);

const app = new Vue({
    router: router,
}).$mount('#app');
