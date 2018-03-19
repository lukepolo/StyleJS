<template>
    <div>
        <div class="col-sm-12">
            <div class="form-group">
                <label>Filter</label>
                <input type="text" class="form-control" v-model="filter">
            </div>
        </div>

        <div class="repository" v-for="repository in remoteRepositories">
            <div>
                {{ repository.full_name }}
            </div>

            <button class="s-button-flat s-button-blue-flat" @click="monitor(repository)">
                <i class="s-new"></i> Start Monitoring
            </button>
        </div>
    </div>
</template>

<script>
    import Vue from 'vue';
    import { mapState } from 'vuex'

    export default Vue.extend({
        data() {
            return {
                filter : null
            }
        },
        methods : {
            monitor(repository) {
                this.$store.dispatch('user/remote_repositories/monitor', {
                    repository_id : repository.id,
                    user_provider_id : repository.user_provider_id,
                }).then((repository) => {
                    this.$router.push({
                        name : 'repositories.repository.settings',
                        params : {
                            repository : repository.id
                        }
                    })
                });
            }
        },
        computed : {
            remoteRepositories() {
                if(this.filter) {
                    return this.repositories.filter((repository) => {
                        return repository.full_name.toLowerCase().includes(this.filter.toLowerCase())
                    })
                }
                return this.repositories;
            },
            ... mapState('user/remote_repositories', {
                repositories : state => state.repositories,
            })
        }
    });
</script>
