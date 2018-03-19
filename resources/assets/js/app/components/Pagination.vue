<template>
    <nav v-if="shouldShowPagination">
        <ul class="pagination justify-content-center">
            <li :class="{ disabled: pagination.meta.current_page === 1 }">
                <a :class="{ disabled: pagination.meta.current_page === 1 }"
                   @click="pageClicked( pagination.meta.current_page - 1 )">
                    <i class="left chevron icon">«</i>
                </a>
            </li>
            <li v-if="hasFirst" class="page-item" :class="{ active: isActive(1) }">
                <a class="page-link" @click="pageClicked(1)">1</a>
            </li>
            <li v-if="hasFirstEllipsis"><span class="pagination-ellipsis">&hellip;</span></li>
            <li class="page-item" :class="{ active: isActive(page), disabled: page === '...' }" v-for="page in pages" :key="page">
                <a class="page-link" @click="pageClicked(page)">{{ page }}</a>
            </li>
            <li v-if="hasLastEllipsis"><span class="pagination-ellipsis">&hellip;</span></li>
            <li v-if="hasLast" class="page-item"
                :class="{ active: isActive(pagination.meta.last_page) }">
                <a class="page-link" @click="pageClicked(pagination.meta.last_page)">{{pagination.meta.last_page}}</a>
            </li>
            <li>
                <a :class="{ disabled: pagination.meta.current_page === pagination.meta.last_page }"
                   @click="pageClicked( pagination.meta.current_page + 1 )">
                    <i class="right chevron icon">»</i>
                </a>
            </li>
        </ul>
    </nav>
</template>

<script>
    import Vue from 'vue';
    export default Vue.extend({
        props: {
            pagination: {
                type: Object,
                default: () => ({}),
            },
            dispatch : {
                type : String,
                required : true,
            },
            parameters : {
                type : Object,
                default: () => ({}),
            }
        },

        computed: {
            pages() {
                return this.pagination.meta.last_page === undefined
                    ? []
                    : this.pageLinks();
            },

            hasFirst() {
                return this.pagination.meta.current_page >= 4 || this.pagination.meta.last_page < 10
            },

            hasLast() {
                return this.pagination.meta.current_page <= this.pagination.meta.last_page - 3 || this.pagination.meta.last_page < 10
            },

            hasFirstEllipsis() {
                return this.pagination.meta.current_page >= 4 && this.pagination.meta.last_page >= 10
            },

            hasLastEllipsis() {
                return this.pagination.meta.current_page <= this.pagination.meta.last_page - 3 && this.pagination.meta.last_page >= 10
            },

            shouldShowPagination() {
                if (this.pagination.meta.last_page === undefined) {
                    return false;
                }

                if (this.pagination.total === 0) {
                    return false;
                }

                return this.pagination.meta.last_page > 1;
            },

        },

        methods: {
            isActive(page) {
                const current_page = this.pagination.meta.current_page || 1;

                return current_page === page;
            },

            pageClicked(page) {
                if (page === '...' ||
                    page === this.pagination.meta.current_page ||
                    page > this.pagination.meta.last_page ||
                    page < 1) {
                    return;
                }
                this.parameters.page = page;
                this.$store.dispatch(this.dispatch, this.parameters)
            },

            pageLinks() {
                const pages = [];

                let left = 2;
                let right = this.pagination.meta.last_page - 1;

                if (this.pagination.meta.last_page >= 10) {
                    left = Math.max(1, this.pagination.meta.current_page - 2);
                    right = Math.min(this.pagination.meta.current_page + 2, this.pagination.meta.last_page);
                }

                for (let i = left; i <= right; i++) {
                    pages.push(i);
                }

                return pages;
            },
        },
    });
</script>