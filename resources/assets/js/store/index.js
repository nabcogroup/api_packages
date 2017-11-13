
import Vue from "vue";
import Vuex from "vuex";

import property from "./property";
import fixedassets from "./fixedassets";

Vue.use(Vuex);

export const store = new Vuex.Store({
    modules: {
        property,
        fixedassets
    }
});