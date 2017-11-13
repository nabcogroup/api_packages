
/**
 * First we will load all of this project's JavaScript dependencies which
 * includes Vue and other libraries. It is a great starting point when
 * building robust, powerful web applications using Vue and Laravel.
 */
require('./bootstrap');

window.Vue = require('vue');

import MyPlugins from "my-vue-tools/src/plugins/plugins";
import Main from "./components/Main.vue";
import router from "./router";
import {store} from "./store";

Vue.use(MyPlugins);

const app = new Vue({
    render: h => h(Main),
    router,
    store
}).$mount("#app");
