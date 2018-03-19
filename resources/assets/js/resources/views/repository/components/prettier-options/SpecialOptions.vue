<template>
    <div>
        <div class="col-sm-6">
            <div class="checkbox">
                <label>
                    <input type="checkbox" name="cli_options.insert-pragma" :checked="insertPragma" @click="updateSync($event.target, 'insert-pragma')" validate> Insert Pragma
                </label>
            </div>
        </div>

        <div class="col-sm-6">
            <div class="checkbox">
                <label>
                    <input type="checkbox" name="cli_options.require-pragma" :checked="requirePragma" @click="updateSync($event.target, 'require-pragma')" validate> Require pragma
                </label>
            </div>
        </div>
    </div>
</template>

<script>
import Vue from "vue";

export default Vue.extend({
  props: ["insertPragma", "requirePragma"],
});
</script>
