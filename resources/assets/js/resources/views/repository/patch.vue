<template>
    <div class="container" v-if="repository && patch">
        <div class="row">
            <div class="col-md-12">
                <div class="flex-space margin-bottom-1">
                    <h1 class="font-size-20">
                        <router-link :to="{ name : 'repositories.repository', params : { repository : repositoryId }}">
                            {{ repository.repository }}
                        </router-link>
                        <span class="go-gray"> - Patch #{{ patch.id }} <patch-status :patch="patch"></patch-status> </span>
                    </h1>
                    <h1 class="font-size-20">
                        <router-link :to="{ name : 'repositories.repository.settings' , params : { repository : repository.id } }" class="s-button-flat s-button-blue-flat">
                            Settings
                        </router-link>
                    </h1>
                </div>
                <div class="panel panel-default">

                    <div class="panel-body">
                        <h3 class="dotted-title">
                            <span>
                                Patch Details
                            </span>
                        </h3>

                        <div>
                            <p>
                                Ran on {{ patch.branch ? patch.branch : 'N/A '}}, <patch-run-date :patch="patch"></patch-run-date> and took {{ patch.runtime }} seconds
                                <a :href="patch.linkToPatch" target="_blank" class="btn btn-default btn-xs pull-right">
                                    <i class="s-github"></i> View on GitHub
                                </a>
                            </p>
                        </div>
                        <div>
                            <pre>{{ patch.log }}</pre>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</template>

<script>
import Vue from "vue";
import { mapState } from "vuex";

export default Vue.extend({
  created() {
    this.$store.dispatch("user/repositories/show", this.repositoryId);
    this.$store.dispatch("user/repositories/patches/show", {
      patch: this.patchId,
      repository: this.repositoryId,
    });
  },
  computed: {
    patchId() {
      return this.$route.params.patch;
    },
    repositoryId() {
      return this.$route.params.repository;
    },
    ...mapState("user/repositories", {
      repository: (state) => state.repository,
    }),
    ...mapState("user/repositories/patches", {
      patch: (state) => state.patch,
    }),
  },
});
</script>
