<template>
    <i data-toggle="tooltip" :title="patch.status" :class="statusClass"></i>
</template>

<script>
    import Vue from 'vue';
    import { mapGetters } from "vuex";

    export default Vue.extend({
        props : ['patch'],
        computed : {
            ...mapGetters({
                patchStatuses : "constants/patchStatuses",
            }),
            statusClass() {
                return this.patchStatuses[this.patch.status]
            }
        }
    })
</script>