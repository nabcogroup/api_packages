import {ErrorValidations} from "my-vue-tools/src/supports/errorValidation";

const state = {
    data: {},
    lookups: {
        
    }
}

const mutations = {
    createNew(state,data) {
        state.data = data.instance;
        state.lookups = data.lookups;
    }
}


const actions = {
    createNew({state,commit},callback) {
        axios.get('/api/fixed-asset/create')
            .then((response) => commit('createNew',response.data))
            .catch((errors) => {
                
            });
    }
}


const getters = {

    lookups(state) {
        return state.lookups
    },
    errors(state) {
        return new ErrorValidations();
    }
}



const module = {
    namespaced: true,
    state,
    mutations,
    actions,
    getters
}



export default module;