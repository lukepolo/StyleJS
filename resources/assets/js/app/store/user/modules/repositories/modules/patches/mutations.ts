import { PatchesState } from "./stateInterface";

export const set = (state: PatchesState, patch) => {
    state.patch = patch;
};

export const setAll = (state: PatchesState, data) => {
    state.paginatedPatches = data;
};

