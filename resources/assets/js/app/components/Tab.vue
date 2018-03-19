<template>
    <div v-show="isVisible">
        <slot></slot>
    </div>
</template>

<script>

import Vue from 'vue';
export default Vue.extend({
    props : {
        name : {
            type : String,
            required : true
        }
    },
    data() {
        return {
            isVisible : false,
        }
    }
})

</script>