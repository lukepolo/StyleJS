<template>
    <div class="col-sm-12">
        <div class="row">
            <h3 class="dotted-title no-margin margin-bottom-1">
                <span>
                    Stop Monitoring Repository
                </span>
            </h3>
            <p class="text-center">
               This action is <strong>permanent</strong>, it's patches history would be wiped out and cannot be undone.
            </p>
        </div>
        <div class="row">
            <div class="col-sm-12 text-center">
                <button @click.prevent="stopMonitoringRepository()" class="s-button s-button-red">Stop monitoring this repository</button>
            </div>
        </div>
    </div>
</template>

<script>
import Vue from "vue";
import { mapState } from "vuex";
export default Vue.extend({
  methods: {
    stopMonitoringRepository() {
      this.$store
        .dispatch("user/repositories/destroy", this.repositoryId)
        .then(() => {
          this.$router.push({
            name: "dashboard",
          });
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
