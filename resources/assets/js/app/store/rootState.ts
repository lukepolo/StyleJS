import {UserState} from "@store/user/stateInterface";
import {RepositoriesState} from "@store/user/modules/repositories/stateInterface";
import {PatchesState} from "@store/user/modules/repositories/modules/patches/stateInterface";
import {BranchesState} from "@store/user/modules/repositories/modules/branches/stateInterface";
import {NotificationsState} from "varie/lib/plugins/notifications/store/notifications/stateInterface";

/*
|--------------------------------------------------------------------------
| Root State
|--------------------------------------------------------------------------
| This manages the root state of the entire application, which allows
| typescript to let us know whats available
|
*/

export default interface rootState {
  user : {
    user : UserState,
    repositories : {
        patches : PatchesState,
        branches : BranchesState,
        repositories : RepositoriesState,
    }
  }
  varie: {
    notifications: {
      notifications: NotificationsState;
    };
  };
};