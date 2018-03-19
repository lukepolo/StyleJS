<template>
    <div>
        <div class="col-sm-12">
            <div class="form-group">
                <label for="prose-wrap">Prose Wrap</label>
                <select name="cli_options.prose-wrap" class="form-control" id="prose-wrap" :value="proseWrap" @input="updateSync($event.target, 'prose-wrap')" validate>
                    <option v-for="option in proseWrapOptions" :value="option">
                        {{ option }}
                    </option>
                </select>
            </div>
        </div>
    </div>
</template>

<script>
import Vue from "vue";
import { mapGetters } from "vuex";

export default Vue.extend({
  props: ["proseWrap"],
  computed: {
    ...mapGetters({
      proseWrapOptions: "constants/proseWrapOptions",
    }),
  },
});
</script>
