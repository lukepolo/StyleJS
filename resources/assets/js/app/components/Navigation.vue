<template>
    <nav class="s-nav">
        <div class="left">
            <router-link to="/" class="logo">
                StyleJS
            </router-link>
        </div>

        <div class="right">
            <router-link to="/" class="item">
                <i class="s-dashboard"></i>
                Dashboard
            </router-link>

            <router-link :to="{ name : 'my-profile' }" class="item">
                <i class="s-user"></i>
                My Account
            </router-link>

            <a href="#" @click="logout()" class="h-red item">
                <i class="s-logout"></i>
                Logout
            </a>
        </div>
    </nav>
</template>

<script>
    import Vue from 'vue';
    export default Vue.extend({
        methods : {
            logout() {
                this.$store.dispatch('user/logout');
            }
        }
    })
</script>