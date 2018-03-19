import state from "./state";
import * as actions from "./actions";
import * as getters from "./getters";
import * as mutations from "./mutations";

export default {
  name: "remote_repositories",
  state,
  actions,
  getters,
  mutations,
  namespaced: true
};
