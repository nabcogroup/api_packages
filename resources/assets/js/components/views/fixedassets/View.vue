<template>
    <v-panel header="Fixed Asset">
        <div class="row">
            <div class="col-md-9">
                <v-label-control label="Purchase Date" v-model="data.purchase_date" class="col-md-6"></v-label-control>
                <v-label-control label="Property" v-model="data.property" class="col-md-6"></v-label-control>
                <v-label-control label="Description" v-model="data.description" class="col-md-12"></v-label-control>
                
            </div>
            <div class="col-md-4"></div>
        </div>
    </v-panel>
</template>

<script>

import { mapState, mapGetters } from "vuex";

export default {
	props: {
    item: Object
  },
  computed: {
    ...mapState("fixedassets", {
      data: state => state.data
    })
  },
  methods: {
		init() {
			const id = this.$route.params.id;
			
			this.$store.dispatch('fixedassets/show',{
			 	id: id,
				callback: (result) => {
					
				}
			});
		}
	},
	mounted() {
		this.init();
	},
	watch: {
		'$route':'init'
	}
};
</script>

