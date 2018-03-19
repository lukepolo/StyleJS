import { UserState } from "./stateInterface";

export const set = (state: UserState, data) => {
    state.user = data;
};
