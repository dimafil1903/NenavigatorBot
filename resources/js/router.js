import VueRouter from 'vue-router'
import User from "./components/User";
import Grapichs from "./components/Grapichs";
import Triggers from "./components/Triggers";
import Auth from "./components/Auth";
import TruthOrAction from "./components/TruthOrAction/TruthOrAction";


export default new VueRouter({
    routes: [
        {
            path: '/welcome',
            component: User
        },
        {
            path: '/stat',
            component: Grapichs
        },
        {
            path: '/stat/:id',
            component: Grapichs
        },
        {
            path: '/triggers/:id',
            component: Triggers
        },
        {
            path: '/auth',
            component: Auth
        },
        {
            path: '/game',
            component: TruthOrAction
        },

    ],
    mode: 'history'
});
