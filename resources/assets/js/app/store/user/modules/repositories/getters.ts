import { RepositoriesState } from "./stateInterface";

export const repository = (state : RepositoriesState) => {
    return state.repository;
};

export const repositories = (state : RepositoriesState) => {
    return state.repositories;
};
