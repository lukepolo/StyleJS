<template>
    <div class="container">
        <div class="row">
            <div class="col-md-8 col-md-offset-2">
                <div class="panel panel-default">
                    <div class="panel-heading">Your Repositories</div>
                    <div class="panel-body">
                        <add-repository></add-repository>
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
    this.$store.dispatch("user/remote_repositories/get");
  },
  computed: {
    ...mapState("user/repositories", {
      repositories: (state) => state.repositories,
    }),
  },
});
</script>
