import { RemoteRepositoriesState } from "./stateInterface";

export const setAll = (state: RemoteRepositoriesState, data) => {
    state.repositories = data;
};
