import { BranchesState } from "./stateInterface";

export const setAll = (state: BranchesState, branches) => {
    state.branches = branches;
};
