<template>
    <div class="container">
        <div class="row">
            <div class="col-md-12">
                <template v-if="repositories.length">
                    <h1>
                        <i class="s-dashboard"></i>
                        Dashboard
                    </h1>
                    <div class="panel panel-default">
                        <div class="panel-heading flex-space">
                            <h4>
                                Monitoring Repositories
                            </h4>

                            <router-link :to="{ name : 'repositories.add' }" class="s-button s-button-green">
                                <i class="s-new"></i>
                                Add Repository
                            </router-link>
                        </div>

                        <div class="panel-body">
                            <table class="table table-patches">
                                <thead>
                                    <tr>
                                        <th>Repository</th>
                                        <th class="text-center">Last Patch</th>
                                        <th></th>
                                    </tr>
                                </thead>
                                <template v-for="repository in repositories">
                                    <repository-row :repository="repository"></repository-row>
                                </template>
                            </table>
                        </div>
                    </div>
                </template>
                <template v-else>
                    <h1 class="margin-bottom-1">
                        Let's Get Started!
                    </h1>
                    <div class="panel panel-default">
                        <div class="panel-body">
                            <h3 class="dotted-title no-margin margin-bottom-1">
							<span>
								Lets start monitoring your first repository
							</span>
                            </h3>
                            <add-repository></add-repository>
                        </div>
                    </div>
                </template>
            </div>
        </div>
    </div>
</template>

<script>
import Vue from "vue";
import { mapState } from "vuex";
import RepositoryRow from "./components/RepositoryRow";

export default Vue.extend({
  components: {
    RepositoryRow,
  },
  created() {
    this.$store.dispatch("user/repositories/get");
  },
  watch: {
    repositories(repositories) {
      if (repositories.length === 0) {
        this.$store.dispatch("user/remote_repositories/get");
      }
    },
  },
  computed: {
    ...mapState("user/repositories", {
      repositories: (state) => state.repositories,
    }),
  },
});
</script>
