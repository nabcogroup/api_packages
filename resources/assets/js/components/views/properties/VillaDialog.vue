<template>
  <v-form-dialog modal-id="villaDialog" v-model="errors" @submit="onSubmit" dialog-title="New Villa/Unit">
      <div class="form-horizontal">
          <v-control-wrapper label="Villa No">
              <v-input-control v-model="villa.villa_no" name="villaNo" ></v-input-control>
              <v-error-span v-model="errors" name="villaNo"></v-error-span>
          </v-control-wrapper>
          <v-control-wrapper label="Description">
              <v-input-control v-model="villa.description" vtype="multiline"></v-input-control>
          </v-control-wrapper>
          <v-control-wrapper label="Electricity No">
              <v-input-control v-model="villa.electricity_no"></v-input-control>
          </v-control-wrapper>
          <v-control-wrapper label="Water No">
              <v-input-control v-model="villa.water_no"></v-input-control>
          </v-control-wrapper>
           <v-control-wrapper label="QTel No">
              <v-input-control v-model="villa.qtel_no"></v-input-control>
          </v-control-wrapper>
          <v-control-wrapper label="Capacity">
              <v-input-control v-model="villa.capacity"></v-input-control>
          </v-control-wrapper>
          <v-control-wrapper label="Rate Per Month">
              <v-input-control v-model="villa.rate_per_month"></v-input-control>
          </v-control-wrapper>
          <v-control-wrapper label="Villa Class">
              <v-combo-box
                      :options="lookups.villa_type"
                      v-model="villa.villa_class"
                      dtext="name"
                      :include-default="true"
                      dvalue="code"></v-combo-box>
          </v-control-wrapper>
      </div>
  </v-form-dialog>
</template>


<script>
import {toggleModal} from "my-vue2-package/src/mixins/mixins";
import {EventBus} from "my-vue2-package";
import {mapState,mapGetters} from "vuex";

export default {

    computed: {
      ...mapState('property',{
          'villa': state => state.villa,
          'lookups': state => state.lookups
      }),
      ...mapGetters('property',{
          errors: 'errors'
      })
  },
  methods: {
      close() {
          
      },
      onSubmit() {
          if(this.villa.id !== undefined && this.villa.id !== 0) {
              this.$store.commit('property/updateVilla')
          }
          else {
              this.$store.commit('property/insertVilla');
          }

          EventBus.$emit('villaDialog.close');
      }
  }
}
</script>
