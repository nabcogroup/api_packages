<template>
    <v-panel header="Edit Property">
        <div class="page-header">
            <h4>Information</h4>
        </div>
        <div class="row">
            <div class="col-sm-8">
                <div class="form-horizontal">
                    <v-control-wrapper label="Property Code " :propertyObject="{alignRight:true}" :required="true">
                        <v-input-control v-model="data.code" name="code" :error-val="errors"></v-input-control>
                    </v-control-wrapper>
                    <v-control-wrapper label="Property Name " :propertyObject="{alignRight:true}" :required="true">
                        <v-input-control v-model="data.name" name="name" :error-val="errors"></v-input-control>
                    </v-control-wrapper>
                    <v-control-wrapper label="Property Address " :propertyObject="{alignRight:true}" :required="true">
                        <v-input-control v-model="data.address" vtype="multiline"></v-input-control>
                    </v-control-wrapper>
                </div>
            </div>
            <div class="col-sm-4">

            </div>

            <div class="col-md-12">
                <v-page-header-bar title="Villas" @click="onClick"></v-page-header-bar>
                <v-grid-view :grid="gridView" :data="data.villas" @action="action"></v-grid-view>
                <villa-dialog></villa-dialog>
            </div>

        </div>
    </v-panel>
</template>

<script>

    import {mapState,mapGetters}  from "vuex";
    import {EventBus} from "my-vue2-package";
    import VillaDialog from "./VillaDialog.vue";

    export default {
        components: {
            VillaDialog
        },
        data() {
            return {
                loading: false,
                gridView: {
                    columns: [
                        {name: 'villa_no', column: 'Villa No'},
                        {name: 'description', column: 'Description'},
                        {name: 'rate_per_month', column: 'Rate/Month', dtype: 'currency'},
                        {name: 'full_status', column: 'Status'}
                    ],
                    actions: {
                        dropdown: [
                            {key:'view',name:'View'},
                            {key: 'edit',name: 'Edit'},
                            {key:'toggle',name: 'Activated'}
                        ]
                    }
                }
            }
        },
        methods: {
            /*Fetch Property*/
            fetchData() {
                const parameter = this.$route.params;
                this.$store.dispatch('property/edit',parameter.id);
            },
            /*Grid View Action*/
            action(action,value,index) {
                if(action === 'edit') {
                    this.$store.commit('property/editVilla',{action: action, villa_no: value.villa_no});
                    EventBus.$emit('villaDialog.show');
                }
            },
            /*Call button */
            onClick() {

            }
        },
        computed: {
            ...mapState('property',{
                data: state => state.data
            }),
            ...mapGetters('property',{
                errors: 'errors'
            })
        },
        mounted() {
            this.fetchData();
        },
        watch: {
            '$route' : 'fetchData'
        }
    }
</script>