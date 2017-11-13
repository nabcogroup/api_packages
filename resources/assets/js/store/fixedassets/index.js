import {ErrorValidations} from "my-vue-tools/src/supports/errorValidation";

const state = {
    data: {},
    lookups: {
        
    },
    errors: new ErrorValidations()
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
    },
    save({state,commit},callback) {
        axios.post('api/fixed-asset/store',state.data)
            .then(response => {
                callback(true);
            })
            .catch(errors => {
                if(errors.response.status === 422) {
                    state.errors.register(errors.response.data.errors)
                }
                callback(false);
            });
    }
}


const getters = {

    lookups(state) {
        return state.lookups
    },
    errors(state) {
        return state.errors;
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