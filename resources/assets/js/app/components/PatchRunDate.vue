<template>
    <span>
        <template v-if="shouldShowTimeAgo">
            <time-ago :time="patch.run_date.date"></time-ago>
        </template>
        <template v-else>
            {{ runDate }}
        </template>
    </span>
</template>

<script>
    import Vue from 'vue';
    import * as moment from 'moment-timezone';

    export default Vue.extend({
        props : ['patch'],
        computed : {
            shouldShowTimeAgo() {
                return moment(this.patch.run_date.date).diff(moment(), 'hours') > -10;
            },
            runDate() {
                return moment(this.patch.run_date.date).format("l LT");
            }
        }
    });
</script>