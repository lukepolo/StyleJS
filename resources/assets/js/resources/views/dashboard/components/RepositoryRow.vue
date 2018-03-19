<template>
    <router-link :to="{ name : 'repositories.repository' , params : { repository : repository.id } }" tag="tr" class="repository-row">
        {{ repository.repository }}
        <td class="text-center">
            <template v-if="repository.last_patch">
                <small><time-ago :time="repository.last_patch.created_at"></time-ago></small>
                <patch-status :patch="repository.last_patch"></patch-status>
            </template>
            <template v-else>
                Never Analyzed
            </template>
        </td>

        <td class="text-center">
            <button @click.prevent="analyzeRepository" class="s-button-flat s-button-blue-flat s-button-skinny">Analyze Now</button>
        </td>
    </router-link>
</template>

<script>
import Vue from "vue";
import RepositoryPatches from "./RepositoryPatches";

export default Vue.extend({
  props: ["repository"],
  components: {
    RepositoryPatches,
  },
  data() {
    return {
      showPatches: false,
    };
  },
  methods: {
    analyzeRepository() {
      this.$store.dispatch("user/repositories/analyze", this.repository.hash);
    },
  },
});
</script>
