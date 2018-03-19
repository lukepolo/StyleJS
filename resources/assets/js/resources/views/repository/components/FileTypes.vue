<template>
    <div class="col-sm-12">
        <div class="form-group">
            <label for="file_types">Select file types you wish to prettyify</label>
            <div class="checkbox" v-for="availableFileType in availableFileTypes">
                <label>
                    <input id="file_types" type="checkbox" name="file_types" :value="availableFileType" :checked="fileTypes.indexOf(availableFileType) > -1" @click="updateSync($event.target)" validate>
                    {{ availableFileType }}
                </label>
            </div>
        </div>
    </div>
</template>

<script>
import Vue from "vue";
import { mapState, mapGetters } from "vuex";

export default Vue.extend({
  props: ["fileTypes"],
  computed: {
    ...mapGetters("constants", {
      availableFileTypes: "fileTypes",
    }),
    ...mapState("user/repositories/branches", {
      branches: (state) => state.branches,
    }),
  },
});
</script>
