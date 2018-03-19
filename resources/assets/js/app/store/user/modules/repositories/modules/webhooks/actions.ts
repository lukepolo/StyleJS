import { ActionContext } from "vuex";
import RootState from "@store/rootState";
import HttpServiceInterface from "varie/lib/http/HttpServiceInterface";

const $http : HttpServiceInterface = $app.make('$http');

export const store = (context: ActionContext<{}, RootState>, repository) => {
    return $http.post(`/api/repository/${repository}/repair/webhook`).then((response) => {
        return response;
    })
};
