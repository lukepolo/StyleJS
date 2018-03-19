<template>
    <tr>
        <td>
            <i data-toggle="tooltip" :title="patch.status" class="\App\Models\RepositoryPatch::STATUSES[$patch->status] }}"></i>
        </td>
        <td>
            {{ patch.branch }}
        </td>
        <td>{{ patch.patch_branch ?  patch.patch_branch  : 'N/A' }}</td>
        <td>{{ patch.runtime }} seconds</td>
        <td>
            <patch-run-date :patch="patch"></patch-run-date>
        </td>
        <td class="text-right">
            <template v-if="patch.log">
                <button class="s-button-flat s-button-blue-flat s-button-skinny" @click="showPatchModal = true">Log</button>
               <modal v-if="showPatchModal">
                   <div class="custom-modal--header">
                       <button type="button" class="close" @click="showPatchModal = false">&times;</button>
                       <h1>Patch Log</h1>
                   </div>
                   <div class="custom-modal--body">
                       <pre>{{ patch.log }}</pre>
                   </div>
                   <div class="custom-modal--footer">
                       <button type="button" class="s-button-flat s-button-blue-flat" @click="showPatchModal = false">Close</button>
                   </div>
               </modal>
            </template>
            <router-link :to="{ name : 'repositories.repository.patches.patch', params : { patch : patch.id, repository : patch.repository} }" class="s-button-flat s-button-blue-flat s-button-skinny">
                Details
            </router-link>
        </td>
    </tr>
</template>

<script>
    import Vue from 'vue';

    export default Vue.extend({
        props : ['patch'],
        data() {
            return {
                showPatchModal : false
            }
        }
    })
</script>