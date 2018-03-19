<template>
    <div class="tabs">
        <div class="panel panel-default tabs-panel">
            <div class="panel-body tabs-panel-body">
                <div class="row tabs-row">
                    <div class="button s-button-skinny" v-for="tab in tabs" :key="tab.id" @click="switchTab(tab.id)" :class="{ active : activeTab === tab.id}">
                        {{ tab.name }}
                    </div>
                </div>
            </div>
        </div>
        <div class="panel panel-default">
            <div class="panel-body">
                <slot></slot>
            </div>
        </div>
    </div>
</template>

<script>
    import Vue from 'vue';

    export default Vue.extend({
        props : ['active'],
        data() {
            return {
                tabs : [],
                activeTab : null,
            }
        },
        mounted() {
            this.tabs = this.$children.map((child, tab) => {
                return {
                    id : tab,
                    name : child.$options.propsData.name
                }
            });
            this.switchTab(0);
        },
        methods : {
            switchTab(tab) {
                Vue.set(this, 'activeTab', tab);
                this.$children.forEach((child, tab) => {
                    let active = this.activeTab === tab;
                    if(active) {
                        this.$emit('update:active', child.name)
                    }
                    Vue.set(child.$data, 'isVisible', active);
                })
            }
        }
    })

</script>