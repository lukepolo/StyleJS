<template>
    <div class="container">
        <div class="row">
            <div class="col-md-12">
                <h1>
                    <i class="s-user"></i>
                    My Account
                </h1>

                <div class="panel panel-default margin-top-2">
                    <div class="panel-body">
                        <h3 class="dotted-title no-margin margin-bottom-1">
                            <span>
                                Account Details
                            </span>
                        </h3>

                        <form @submit.prevent="updateProfile()" v-form="form">

                            <div class="form-group">
                                <label for="name">Name</label>
                                <input type="text" class="form-control" v-model="form.name" name="name" id="name" validate>
                            </div>

                            <div class="form-group">
                                <label for="email">Email</label>
                                <input type="email" class="form-control" v-model="form.email" name="email" id="email" validate>
                            </div>

                            <button type="submit" class="s-button s-button-green" :disabled="!form.isValid()">
                                Update
                            </button>
                        </form>
                    </div>
                </div>
            </div>
        </div>

        <div class="row">
            <div class="col-md-12 text-center">
                <button type="button" class="s-button s-button-red" @click="showDeleteAccountModal = true">
                    Delete Account
                </button>

                <modal v-if="showDeleteAccountModal">
                    <div class="custom-modal--header">
                        <button type="button" class="close" @click="showDeleteAccountModal = false">&times;</button>
                        <h1>Delete Account</h1>
                    </div>
                    <div class="custom-modal--body">
                        <p>
                            Are you sure you want to delete your StyleJS account? This action is <strong>permanent</strong> and can not be undone.
                        </p>
                    </div>
                    <div class="custom-modal--footer">
                        <button type="submit" class="s-button s-button-red" @click="deleteAccount">
                            Yes, I'm sure
                        </button>
                    </div>
                </modal>
            </div>
        </div>
    </div>
</template>

<script>
import Vue from "vue";
import { mapState } from "vuex";
import UpdateUserValidator from "@app/validators/UpdateUserValidator";

export default Vue.extend({
  data() {
    return {
      form: this.createForm({
        name: null,
        email: null,
      }).validation(new UpdateUserValidator()),
      showDeleteAccountModal: false,
    };
  },
  created() {
    this.form.name = this.user.name;
    this.form.email = this.user.email;
  },
  methods: {
    updateProfile() {
      this.$store.dispatch("user/update", this.form).then(() => {
        this.notificationService.showSuccess("You have updated your profile.");
      });
    },
    deleteAccount() {
      this.$store.dispatch("user/destroy");
    },
  },
  computed: {
    ...mapState("user", {
      user: (state) => state.user,
    }),
  },
});
</script>
