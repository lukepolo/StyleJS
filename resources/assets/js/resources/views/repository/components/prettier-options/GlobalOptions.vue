<template>
    <div>
        <div class="col-sm-12">
            <div class="form-group">
                <label for="print-width">Print Width</label>
                <input type="number" class="form-control" :value="printWidth" @input="updateSync($event.target, 'print-width')" min="0" name="cli_options.print-width" id="print-width" validate>
            </div>
        </div>

        <div class="col-sm-12">
            <div class="form-group">
                <label for="tab-width">Tab / Spaces Width</label>
                <input type="number" class="form-control" :value="tabWidth" @input="updateSync($event.target, 'tab-width')" name="cli_options.tab-width" min="0" id="tab-width" validate>
            </div>
        </div>

        <div class="col-sm-12">
            <div class="checkbox">
                <label>
                    <input type="checkbox" :checked="useTabs" @click="updateSync($event.target, 'use-tabs')" name="cli_options.use-tabs" validate>
                    Use Tabs instead of spaces
                </label>
            </div>
        </div>
    </div>
</template>

<script>
import Vue from "vue";

export default Vue.extend({
  props: ["useTabs", "tabWidth", "printWidth"],
});
</script>
