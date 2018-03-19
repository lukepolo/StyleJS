<template>
    <div class="container" v-if="repository && form">
        <div class="row">
            <div class="col-md-12">
                <div class="flex-space margin-bottom-1">
                    <h1 class="font-size-20">
                        <router-link :to="{ name : 'repositories.repository', params : { repository : repositoryId }}">
                            {{ repository.repository }}
                        </router-link>
                        <span class="go-gray">Settings</span>
                    </h1>
                </div>
                <form method="POST" @submit.prevent="updateRepository" v-form="form">
                    <tabs :active.sync="activeTab">
                        <tab name="Automation">
                            <automation-level
                                :defaultBranch.sync="form.default_branch"
                                :branches.sync="form.branches"
                                :noCi.sync="form.no_ci"
                                :onDemand.sync="form.on_demand"
                                :analysisSetting.sync="form.analysis_setting"
                            ></automation-level>
                        </tab>
                        <tab name="File Types">
                            <file-types
                                :fileTypes.sync="form.file_types"
                            ></file-types>
                        </tab>
                        <tab name="Directories and Files">
                            <directories-and-files
                                :ignoreDirectories.sync="form.ignore_directories"
                                :includeDirectories.sync="form.include_directories"
                            ></directories-and-files>
                        </tab>
                        <tab name="Pretting Settings">
                            <prettier-settings
                                :options.sync="form.cli_options"
                            ></prettier-settings>
                        </tab>
                        <tab name="Webhooks">
                            <webhooks
                            ></webhooks>
                        </tab>
                        <tab name="Stop Monitoring Repository">
                            <stop-monitoring-repository></stop-monitoring-repository>
                        </tab>
                    </tabs>

                    <div class="row" v-if="activeTab !== 'Webhooks' && activeTab !== 'Stop Monitoring Repository'">
                        <div class="col-sm-12">
                            <button type="submit" class="margin-auto s-button s-button-green" :disabled="!form.isValid()">
                                Update Repository
                            </button>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>
</template>

<script>
import Vue from "vue";
import { mapState } from "vuex";
import Webhooks from "./components/Webhooks";
import FileTypes from "./components/FileTypes";
import RepositoryModel from "@app/models/RepositoryModel";
import AutomationLevel from "./components/AutomationLevel";
import PrettierSettings from "./components/PrettierSettings";
import DirectoriesAndFiles from "./components/DirectoriesAndFiles";
import StopMonitoringRepository from "./components/StopMonitoringRepository";
import UpdateRepositoryValidator from "@app/validators/UpdateRepositoryValidator";

export default Vue.extend({
  components: {
    Webhooks,
    FileTypes,
    AutomationLevel,
    PrettierSettings,
    DirectoriesAndFiles,
    StopMonitoringRepository,
  },
  data() {
    return {
      form: null,
      activeTab: null,
    };
  },
  created() {
    this.fetchData();
  },
  watch: {
    $route: "fetchData",
  },
  methods: {
    fetchData() {
      this.$store
        .dispatch("user/repositories/show", this.repositoryId)
        .then((repository) => {
          if (
            repository.cli_options &&
            Object.keys(repository.cli_options).length === 0
          ) {
            delete repository.cli_options;
          }
          let repositoryModel = new RepositoryModel(repository);
          Vue.set(
            this,
            "form",
            this.createForm(repositoryModel).validation(
              new UpdateRepositoryValidator(),
            ),
          );
          this.form.setOriginaldata();
          this.$store.dispatch(
            "user/repositories/branches/get",
            this.repositoryId,
          );
        });
    },
    updateRepository() {
      this.$store
        .dispatch("user/repositories/update", {
          form: this.form,
          repository: this.repositoryId,
        })
        .then(() => {
          this.notificationService.showSuccess(
            "You have updated the repository's settings",
          );
        });
    },
  },
  computed: {
    repositoryId() {
      return this.$route.params.repository;
    },
    ...mapState("user/repositories", {
      repository: (state) => state.repository,
    }),
  },
});
</script>
