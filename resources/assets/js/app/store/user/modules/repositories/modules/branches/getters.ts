import { BranchesState } from "./stateInterface";

export const repository = (state : BranchesState) => {
    return state.branches;
};
