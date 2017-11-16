import {ErrorValidations} from "my-vue2-package";

const state = {
    data: {
        depreciations:[]
    },
    lookups: {
        
    },
    errors: new ErrorValidations()
}

const mutations = {
    createNew(state,data) {
        state.data = data.instance;
        state.lookups = data.lookups;
    },
    show(state,data) {
        state.data = data.instance;
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
    },
    show({state,commit},payload) {
        const url = 'api/fixed-asset/show/' + payload.id;
        axios
            .get(url)
            .then((response) => {
                commit('show',response.data);
                
            })
            .catch((errors) => {

                payload.callback(false);
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