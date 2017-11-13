import {ErrorValidations} from "my-vue-tools/src/supports/errorValidation";
import {cloneObject} from "my-vue-tools/src/supports/helper";



const state = {
    data: {
        code: "",
        name: "",
        address: "",
        villas: []
    },
    villa: {
        villaNo: '',
        description: '',
        electricityNo: '',
        waterNo:'',
        qtelNo:'',
        capacity:'',
        ratePerMonth: '',
        villaClass:''
    },
    errors: new ErrorValidations()
}


const mutations = {
    insertVilla(state) {
        const villa = cloneObject(state.villa);
        state.data.villas.push(villa);
    },
    clearInstance(state) {
        
    }
}


const actions = {

}


const getters = {
    errors() {
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