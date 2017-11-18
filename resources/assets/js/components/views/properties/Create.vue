<template>
 <v-panel header="Create Property">
   <div class="page-header">
     <h4>Information</h4>
   </div>
   <div class="row">
		<div class="col-sm-8">
			<div class="form-horizontal">
				<v-control-wrapper label="Property Code " :propertyObject="{alignRight:true}" :required="true">
					<v-input-control v-model="data.code"></v-input-control>
				</v-control-wrapper>
				<v-control-wrapper label="Property Name " :propertyObject="{alignRight:true}" :required="true">
					<v-input-control v-model="data.name"></v-input-control>
				</v-control-wrapper>
				<v-control-wrapper label="Property Address " :propertyObject="{alignRight:true}" :required="true">
					<v-input-control v-model="data.address" vtype="multiline"></v-input-control>
				</v-control-wrapper>
			</div>
		</div>
		<div class="col-sm-4"></div>
   </div>
   
  <div class="row">
    <div class="col-md-12">
      <v-page-header-bar title="Villas" @click="onClick"></v-page-header-bar>
      <v-grid-view :grid="gridView" :data="data.villas"></v-grid-view>
      <villa-dialog></villa-dialog>
    </div>
  </div>   
  </v-panel>
  
</template>

<script>
import {mapState} from "vuex";
import VillaDialog from "./VillaDialog";
import {EventBus} from "my-vue2-package";

export default {
  components: {
    VillaDialog
  },
  data() {
    return {
      gridView: {
        columns: [
          {name:'villaNo',column:'Villa No'},
          {name:'description',column:'Description'},
          {name:'ratePerMonth',column:'Rate/Month', dtype:'currency'}
        ]
      }
    }
  },
  computed: {
    ...mapState('property', {
      data: state => state.data
    })
  },
  methods: {
    onClick(value) {
      if(value == 'add') {
        EventBus.$emit('villaDialog.show');
      }
    }
  }
};
</script>
