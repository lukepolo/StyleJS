import { ActionContext } from "vuex";
import RootState from "@store/rootState";
import { PatchesState } from "./stateInterface";
import HttpServiceInterface from "varie/lib/http/HttpServiceInterface";

const $http : HttpServiceInterface = $app.make('$http');

export const get = (context: ActionContext<PatchesState, RootState>, { repository , page }) => {
    return $http.get(`/api/repository/${repository}/patches${ page ? `?page=${page}` : '' }`).then((response) => {
        context.commit('setAll', response.data)
        return response.data;
    })
};

export const show = (context: ActionContext<PatchesState, RootState>, { repository, patch }) => {
    return $http.get(`/api/repository/${repository}/patches/${patch}`).then((response) => {
        context.commit('set', response.data)
        return response.data;
    })
};