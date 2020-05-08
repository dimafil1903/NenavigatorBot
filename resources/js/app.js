require('./bootstrap');

window.Vue = require('vue');

import VueRouter from 'vue-router'
import router from "./router";
import App from "./components/App";
import axios from "axios";
import VueGoogleCharts from 'vue-google-charts'
import animatecss from 'animate.css'
//import VueAxios from "vue-axios"
import VueCookies from 'vue-cookies'


import {AvatarPlugin, BootstrapVue, IconsPlugin} from 'bootstrap-vue'


// Install BootstrapVue
Vue.use(BootstrapVue)
// Optionally install the BootstrapVue icon components plugin
Vue.use(AvatarPlugin)
Vue.use(IconsPlugin)

Vue.use(animatecss);


// const files = require.context('./', true, /\.vue$/i);
// files.keys().map(key => Vue.component(key.split('/').pop().split('.')[0], files(key).default));

//Vue.component('example-component', require('./components/ExampleComponent.vue').default);


Vue.use(VueRouter);
Vue.use(axios);
Vue.use(VueGoogleCharts);

//vue cookies
Vue.use(VueCookies)
Vue.$cookies.config('7d')


const app = new Vue({
    el: '#app',
    render: h => h(App),
    router
});

