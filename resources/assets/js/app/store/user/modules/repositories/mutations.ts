import { RepositoriesState } from "./stateInterface";

export const setAll = (state: RepositoriesState, data) => {
    state.repositories = data;
};

export const set = (state: RepositoriesState, data) => {
    state.repository = data;
};

export const add = (state : RepositoriesState, data) => {
    state.repositories.push(data);
};

export const remove = (state: RepositoriesState, repository) => {
    let repositoryIndex = state.repositories.map(function(item) { return item.id; }).indexOf(repository);
    if(repositoryIndex !== -1) {
        state.repositories.splice(repositoryIndex, 1);
    }
};