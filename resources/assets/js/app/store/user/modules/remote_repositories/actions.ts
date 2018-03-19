import { ActionContext } from "vuex";
import RootState from "@store/rootState";
import { RemoteRepositoriesState } from "./stateInterface";
import HttpServiceInterface from "varie/lib/http/HttpServiceInterface";

const $http : HttpServiceInterface = $app.make('$http');

export const get = (context: ActionContext<RemoteRepositoriesState, RootState>) => {

    let stateRepositories = context.state.repositories;
    if(stateRepositories.length) {
        return stateRepositories;
    }

    return $http.get('/api/remote-repositories').then((response) => {
        context.commit('setAll', response.data);
        return response.data;
    })
};

export const monitor = (context: ActionContext<RemoteRepositoriesState, RootState>, data) => {
    return $http.post('/api/remote-repositories', data).then((response) => {
        context.commit('user/repositories/add', response.data, { root: true })
        return response.data;
    })
};