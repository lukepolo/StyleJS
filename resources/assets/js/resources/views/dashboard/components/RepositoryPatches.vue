<template>
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
            <template v-if="patches.length === 0">
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
</template>

<script>
import Vue from "vue";

export default Vue.extend({
  props: ["patches"],
});
</script>
