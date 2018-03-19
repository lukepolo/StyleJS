import { ActionContext } from "vuex";
import RootState from "@store/rootState";
import { BranchesState } from "./stateInterface";
import HttpServiceInterface from "varie/lib/http/HttpServiceInterface";

const $http : HttpServiceInterface = $app.make('$http');

export const get = (context: ActionContext<BranchesState, RootState>, repository) => {
    return $http.get(`/api/repository/${repository}/branches`).then((response) => {
        context.commit('setAll', response.data)
        return response.data;
    })
};
