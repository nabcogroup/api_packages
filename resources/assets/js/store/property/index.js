import {ErrorValidations} from "my-vue2-package";
import {cloneObject,copiedValue} from "my-vue2-package";

const state = {
    data: {
        code: "",
        name: "",
        address: "",
        villas: [],
    },
    villa: {
        villa_no: '',
        description: '',
        electricity_no: '',
        water_no: '',
        qtel_no: '',
        capacity: '',
        rate_per_month: '',
        villa_class: ''
    },
    lookups: {
        villa_type: []
    },
    errors: new ErrorValidations()
}


const mutations = {
    insertVilla(state) {
        const villa = cloneObject(state.villa);
        state.data.villas.push(villa);
        this.commit('property/clearInstance');
    },
    editVilla(state,payload) {
        const villa = _.find(state.data.villas,(villa) => {
            return villa.villa_no === payload.villa_no;
        });
        this.commit('property/clearInstance');
        if(villa) state.villa = cloneObject(villa);
    },
    updateVilla(state,payload) {

        let editVilla = _.find(state.data.villas,(villa) => {
            return villa.id == state.villa.id
        });
        copiedValue(state.villa,editVilla);
    },
    clearInstance(state) {
        state.villa.villa_no = "";
        state.villa.description = "";
        state.villa.electricity_no = "";
        state.villa.water_no = "";
        state.villa.qtel_no = "";
        state.villa.capacity = "";
        state.villa.rate_per_month = "0";
        state.villa.villa_class = "";

    }
}

const actions = {
    create({state}) {
        axios.get("/api/property/create")
            .then((response) => {

                state.data = response.data.data;

                state.lookups = response.data.lookups;

            });
    },
    edit({state}, id) {

        axios.get("/api/property/" + id + "/edit/")
            .then((response) => {
                state.data = response.data.data;
                state.lookups = response.data.lookups;
            });
    },
    save({commit, state}) {

        axios.post("/api/property/store", state.data)
            .then((response) => {

            })
            .catch((errors) => {
                if(errors.response.status == 422) {
                    state.errors.register(errors.response.data.errors);
                }
            });
    },
    update({commit, state}) {
        axios.patch("/api/property/update", state.data)
            .then((response) => {

            })
            .catch((errors) => {

            })
    }
}


const getters = {
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
