import { ActionContext } from "vuex";
import RootState from "@store/rootState";
import { UserState } from "./stateInterface";
import HttpServiceInterface from "varie/lib/http/HttpServiceInterface";

const $http : HttpServiceInterface = $app.make('$http');

export const update = (context: ActionContext<UserState, RootState>, form) => {
    return $http.post('/api/user', form).then((response) => {
        context.commit('set', response.data);
        return response.data;
    })
};

export const destroy = (context: ActionContext<UserState, RootState>, form) => {
    return $http.delete(`/api/user/${context.state.user.id}`).then(() => {
        window.location.href = "/";
    })
};
export const logout = () => {
    return $http.post('/logout')
        .then(() => {
            window.location.href = "/";
        });
};
