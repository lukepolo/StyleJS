import { ActionContext } from "vuex";
import RootState from "@store/rootState";
import { RepositoriesState } from "./stateInterface";
import HttpServiceInterface from "varie/lib/http/HttpServiceInterface";

const $http : HttpServiceInterface = $app.make('$http');

export const get = (context: ActionContext<RepositoriesState, RootState>) => {
    let stateRepositories = context.state.repositories;

    if(stateRepositories.length) {
        return stateRepositories;
    }

    return $http.get('/api/repositories').then((response) => {
        context.commit('setAll', response.data);
        return response.data;
    })
};

export const show = (context: ActionContext<RepositoriesState, RootState>, repository) => {
    let stateRepository = context.state.repository;

    if(stateRepository && stateRepository.id === repository) {
        return stateRepository;
    }

    return $http.get(`/api/repositories/${repository}`).then((response) => {
        context.commit('set', response.data);
        return response.data;
    })
};

export const update = (context: ActionContext<RepositoriesState, RootState>, data) => {
    return $http.put(`/api/repositories/${data.repository}`, data.form.data()).then((response) => {
        return response.data;
    })
};

export const analyze = (context: ActionContext<RepositoriesState, RootState>, repositoryHash) => {
    return $http.get(`/webhook/repository/${repositoryHash}/analyze`).then((response) => {
        return response.data;
    })
};

export const destroy = (context: ActionContext<RepositoriesState, RootState>, repository) => {
    return $http.delete(`/api/repositories/${repository}`).then((response) => {
        context.commit('remove', repository)
        return response;
    })
};
