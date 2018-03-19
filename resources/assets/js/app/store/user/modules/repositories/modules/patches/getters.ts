import { PatchesState } from "./stateInterface";

export const patch = (state : PatchesState) => {
    return state.patch;
};

export const paginatedPatches = (state : PatchesState) => {
    return state.paginatedPatches;
};