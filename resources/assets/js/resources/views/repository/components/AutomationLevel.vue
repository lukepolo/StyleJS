<template>
    <div>
        <div class="col-sm-6">
            <i class="glyphicon glyphicon-info-sign" data-toggle="tooltip" title="when analysis starts if a branch is not supplied you can set a default"></i>
            <label for="default_branch">Default Branch</label>

            <select id="default_branch" name="default_branch" class="form-control" :value="defaultBranch" @input="updateSync($event.target)" v-if="repositoryBranches.length" validate>
                <option></option>
                <option v-for="branch in repositoryBranches" :value="branch">
                    {{ branch }}
                </option>
            </select>
        </div>

        <div class="col-sm-6">
            <i class="glyphicon glyphicon-info-sign" data-toggle="tooltip" title="by not selecting anything means all branches are subject for analysis"></i>
            <label for="branches">Branches Available for Analysis</label>
            <select name="branches" id="branches" multiple class="form-control" @input="updateSync($event.target)" v-if="repositoryBranches.length" validate>
                <option v-for="branch in repositoryBranches" :value="branch" :selected="branches.indexOf(branch) > -1">
                    {{ branch }}
                </option>
            </select>
        </div>

        <div class="col-sm-6">
            <div class="checkbox">
                <label>
                    <input type="checkbox" name="no_ci" :checked="noCi" @click="updateSync($event.target)" validate>
                    Skip Continuous Integrations
                </label>
            </div>
        </div>

        <div class="col-sm-6">
            <div class="checkbox">
                <label>
                    <input type="checkbox" name="on_demand" :checked="onDemand" @click="updateSync($event.target)" validate>
                    Run Analysis Manually
                </label>
            </div>
        </div>

        <h3 class="dotted-title">
            <span>
                Automation Level
            </span>
        </h3>

        <div class="radio" v-for="(label, setting) in analysisOptions">
            <label>
                <input type="radio" name="analysis_setting" :value="setting" @click="updateSync($event.target)" :checked="analysisSetting === setting" validate>
                {{ label }}
            </label>
        </div>
    </div>
</template>

<script>
import Vue from "vue";
import { mapState, mapGetters } from "vuex";

export default Vue.extend({
  props: ["defaultBranch", "branches", "noCi", "onDemand", "analysisSetting"],
  computed: {
    ...mapGetters("constants", {
      analysisOptions: "analysisOptions",
    }),
    ...mapState("user/repositories/branches", {
      repositoryBranches: (state) => state.branches,
    }),
  },
});
</script>
