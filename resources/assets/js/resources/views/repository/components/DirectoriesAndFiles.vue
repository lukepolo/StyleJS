<template>
    <div class="col-sm-12">
        <template>
            <div class="form-group">
                <label for="include_directories">Included Files and Paths</label>
                <small>(separated by rows)</small>
                <textarea rows="7" name="include_directories" id="include_directories" class="form-control" :value="includeDirectories" @input="updateSync($event.target)" validate></textarea>
            </div>
        </template>
        <template>
            <div class="form-group">
                <label for="ignore_directories">Ignored Files and Paths</label>
                <small>(separated by rows)</small>
                <textarea rows="7" name="ignore_directories" id="ignore_directories" class="form-control" :value="ignoreDirectories" @input="updateSync($event.target)" validate></textarea>
            </div>
        </template>
    </div>
</template>

<script>
import Vue from "vue";

export default Vue.extend({
  props: ["ignoreDirectories", "includeDirectories"],
});
</script>
