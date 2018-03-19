<template>
    <div class="container" v-if="repository">
        <div class="row">
            <div class="col-md-12">
                <div class="flex-space margin-bottom-1">
                    <h1 class="font-size-20">
                        <span>{{ repository.repository }} <span class="go-gray">Patches</span></span>
                        <br>
                        <span class="go-gray">
                            <img :src="badgeUrl">
                            <button data-toggle="tooltip" title="Copy the markdown required to see the badge in your repository"  class="copy-markdown s-button s-button-blue" v-clipboard:copy="badgeInMarkdown" >
                                <i class="glyphicon glyphicon-copy"></i>
                            </button>
                        </span>
                    </h1>

                    <h1 class="font-size-20">
                        <div class="s-button-flat s-button-blue-flat" @click="analyzeRepository">
                            Analyze Now
                        </div>
                        <router-link :to="{ name : 'repositories.repository.settings' , params : { repository : repository.id } }" class="s-button-flat s-button-blue-flat">
                            Settings
                        </router-link>
                    </h1>
                </div>

                <div class="panel panel-default">
                    <div class="panel-body">
                        <template v-if="patches && patches.length">
                            <pagination :pagination="pagination" dispatch="user/repositories/patches/get" :parameters="{ repository : this.repositoryId }"></pagination>
                            <table class="table table-striped">
                                <thead>
                                <tr>
                                    <th></th>
                                    <th>Base Branch</th>
                                    <th>Patch Branch</th>
                                    <th>Runtime</th>
                                    <th>Date</th>
                                    <th></th>
                                </tr>
                                </thead>
                                <tbody>
                                <template v-if="!patches">
                                    <tr>
                                        <td class="text-center" colspan="7">
                                            <em>This repository hasn't had any patches yet.</em>
                                        </td>
                                    </tr>
                                </template>
                                <template v-else>
                                    <repository-patch :patch="patch" v-for="patch in patches" :key="patch.id"></repository-patch>
                                </template>
                                </tbody>
                            </table>
                            <pagination :pagination="pagination" dispatch="user/repositories/patches/get" :parameters="{ repository : this.repositoryId }"></pagination>
                        </template>
                        <template v-else>
                            <div class="text-center">
                                This repository has not been analyzed yet.
                            </div>
                        </template>
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
    this.$store.dispatch("user/repositories/patches/get", {
      repository: this.repositoryId,
    });
  },
  computed: {
    repositoryId() {
      return this.$route.params.repository;
    },
    ...mapState("user/repositories", {
      repository: (state) => state.repository,
    }),
    ...mapState("user/repositories/patches", {
      pagination: (state) => state.paginatedPatches,
      patches: (state) =>
        state.paginatedPatches ? state.paginatedPatches.data : null,
    }),
    badgeUrl() {
      return `/repository/${this.repositoryId}/badge?branch=${
        this.repository.default_branch
          ? this.repository.default_branch
          : "master"
      }`;
    },
    badgeInMarkdown() {
      return `[![StyleJS](${this.badgeUrl})](repository/${
        this.repositoryId
      }/patches)`;
    },
  },
  methods: {
    analyzeRepository() {
      this.$store
        .dispatch("user/repositories/analyze", this.repository.hash)
        .then(() => {
          this.notificationService.showSuccess(
            "Your repository has been queued to be analyzed.",
          );
        });
    },
  },
});
</script>
