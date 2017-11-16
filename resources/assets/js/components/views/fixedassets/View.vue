<template>
    <v-panel header="Fixed Asset">
        <div class="row">
            <div class="col-md-9">
                <v-label-control label="Purchase Date" :value="data.full_purchase_date" class="col-md-6"></v-label-control>
                <v-label-control label="Property" :value="data.full_property" class="col-md-6"></v-label-control>
                <v-label-control label="Description" :value="data.description" class="col-md-12"></v-label-control>
                <v-label-control label="Fixed Asset Type" :value="data.full_fixed_asset_type" class="col-md-6"></v-label-control>
                <v-label-control label="Serial No" :value="data.serial_no" class="col-md-6"></v-label-control>
                <v-label-control label="Purchase Cost" :value="data.cost" class="col-md-6"></v-label-control>
                <v-label-control label="Year Span" :value="data.year_span" class="col-md-6"></v-label-control>
                <v-label-control label="Salvage Value" :value="data.salvage_value" class="col-md-6"></v-label-control>
            </div>
            <div class="col-md-4"></div>
        </div>
        <div class="row">
            <div class="col-md-12">
                <v-grid-view :grid="gridView" :data="data.depreciations"></v-grid-view>
            </div>
        </div>
    </v-panel>
</template>

<script>

import { mapState, mapGetters } from "vuex";

export default {
  props: {
    item: Object
  },
  data() {
    return {
      gridView: {
        columns: [
          {name: 'ob_year',column:'Opening Balance Year', style:"width:20%", class:'text-center'},
          {name: 'ob_amount',column:'Opening Balance Amount',style:"width:20%", class:'text-center',dtype:'currency'},
          {name: 'depreciated_value',column:'Depreciated Value',style:"width:20%", class:'text-center',dtype:'currency'},
          {name: 'book_value',column:'Book Value',dtype:'currency'}
        ]
      },
      dataset: {}
    }
  },
  computed: {
    ...mapState("fixedassets", {
      data: state => state.data
    })
  },
  methods: {
    init() {
      const id = this.$route.params.id;
      console.log(id);
      this.$store.dispatch("fixedassets/show", {
        id: id,
        callback: result => {}
      });
    }
  },
  mounted() {
    this.init();
  },
  watch: {
    $route: "init"
  }
};
</script>

