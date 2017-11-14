<template>
<div class="row">
    <v-page-header-bar title="Fixed Asset List" @click="onButtonClick"></v-page-header-bar>
    <v-live-view :grid="gridView"></v-live-view>
    <fixed-asset-dialog></fixed-asset-dialog>
</div>
</template>

<script>

import { EventBus } from "my-vue-tools/src/events/eventbus";
import FixedAssetDialog from "./FixedAssetDialog";

export default {
  
  components: {
    FixedAssetDialog
  },
  data() {
    return {
      gridView: {
        columns: [
          { name: "full_purchase_date", column: "Purchase Date" },
          { name: "description", column: "Description" },
          { name: "full_property", column: "Property", filter:true},
          { name: "full_fixed_asset_type", column: "Fixed Asset Type" },
          {name: "$action", column: "Action", style:"width:6%"}
        ],
        source: {
          url: "/api/fixed-asset/"
        },
        actions: [
          {key:'view',name: 'View'},
          {key:'edit',name: 'Edit'}
        ],
      }
    };
  },
  methods: {
    onButtonClick(value) {
      if (value == "add") {
        EventBus.$emit("fixedAssetDialog.show");
      }
    }
  }
};
</script>

