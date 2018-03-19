<template>
    <div class="col-sm-12">
        <h3 class="dotted-title no-margin margin-bottom-1">
            <span>
                Webhook URL
            </span>
        </h3>
        <div class="flex-space">
            {{ webhookUrl }}
            <button type="button" data-toggle="tooltip" title="Copy webhook url"  class="copy-webhook s-button s-button-blue"  v-clipboard:copy="webhookUrl">
                <i class="glyphicon glyphicon-copy"></i>
            </button>
        </div>

        <div class="flex-space margin-bottom-1">
            <div>
                If you deleted our <strong>deploy key</strong> on your repository, you can repair it:
            </div>

            <button class="s-button-flat s-button-blue-flat s-button-skinny margin-left-1" @click="repairWebhook">
                Repair Webhook
            </button>
        </div>
    </div>
</template>

<script>
import Vue from "vue";
import { mapState } from "vuex";

export default Vue.extend({
  methods: {
    repairWebhook() {
      this.$store
        .dispatch("user/repositories/webhooks/store", this.repositoryId)
        .then(() => {
          this.notificationService.showSuccess(
            "Your webhook has been repaired",
          );
        });
    },
  },
  computed: {
    webhookUrl() {
      return `${location.origin}/webhook/analyze/${this.repository.hash}`;
    },
    repositoryId() {
      return this.$route.params.repository;
    },
    ...mapState("user/repositories", {
      repository: (state) => state.repository,
    }),
  },
});
</script>
