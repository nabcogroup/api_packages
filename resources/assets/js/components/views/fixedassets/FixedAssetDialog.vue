<template>
  <v-form-dialog 
    dialog-title="New Fixed Asset" 
    modal-id="fixedAssetDialog" 
    v-model="errors" 
    @submit="onSubmit" 
    @onShow="onShow"
    class="form-horizontal"
    :loading-animation="isLoadingSave"
    >
      
    <v-control-wrapper label="Purchase Date">
      <v-date-picker v-model="data.purchase_date"></v-date-picker>
      <v-error-span v-model="errors" name="purchase_date"></v-error-span>
    </v-control-wrapper>

    <v-control-wrapper label="Description">
      <v-input-control v-model="data.description" name="description" vtype="multiline"></v-input-control>
      <v-error-span v-model="errors" name="description"></v-error-span>
    </v-control-wrapper>

    <v-control-wrapper label="Fixed Asset Type">
      <v-combo-box 
        :options="lookups.fixed_asset_type" 
        v-model="data.fixed_asset_type" 
        dvalue="code" 
        dtext="name" 
        :include-default="true" 
        name="fixed_asset_type"></v-combo-box>
      <v-error-span v-model="errors" name="fixed_asset_type"></v-error-span>
    </v-control-wrapper>

    <v-control-wrapper label="Property">
      <v-combo-box :options="lookups.villa_location" v-model="data.property_code" dvalue="code" dtext="name" :include-default="true" name="property_code"></v-combo-box>
      <v-error-span v-model="errors" name="property_code"></v-error-span>
    </v-control-wrapper>

    <v-control-wrapper label="Serial Number">
      <v-input-control v-model="data.serial_no" name="serial_no"></v-input-control>
      <v-error-span v-model="errors" name="serial_no"></v-error-span>
    </v-control-wrapper>

    <v-control-wrapper label="Purchase Cost">
      <v-input-control v-model="data.cost" name="cost" vtype="number"></v-input-control>
      <v-error-span v-model="errors" name="cost"></v-error-span>
    </v-control-wrapper>

    <v-control-wrapper label="Year Span">
      <v-input-control v-model="data.year_span" name="year_span" vtype="number"></v-input-control>
    </v-control-wrapper>

    <v-control-wrapper label="Salvage Value">
      <v-input-control v-model="data.salvage_value" name="salvage_value" vtype="number"></v-input-control>
      <v-error-span v-model="errors" name="salvage_value"></v-error-span>
    </v-control-wrapper>

  </v-form-dialog>
</template>

<script>
import { mapGetters, mapState } from "vuex";
import { EventBus } from "my-vue-tools/src/events/eventbus";

export default {
  data() {
      return {
        isLoadingSave: false
      }
  },
  computed: {
    
    ...mapState("fixedassets", {
      data: state => state.data
    }),
    ...mapGetters("fixedassets", {
      lookups: "lookups",
      errors: "errors"
    })
  },
  methods: {
    onSubmit() {
      this.isLoadingSave = true;
      this.$store.dispatch("fixedassets/save", result => {
        this.isLoadingSave = false;
        if (result == true) {
          EventBus.$emit("fixedAssetDialog.close");
          EventBus.$emit("onLiveViewFetch");
        }
      });
      
    },
    onShow() {
      this.$store.dispatch("fixedassets/createNew");
    }
  }
};
</script>

