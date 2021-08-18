/**
 * First we will load all of this project's JavaScript dependencies which
 * includes Vue and other libraries. It is a great starting point when
 * building robust, powerful web applications using Vue and Laravel.
 */

require("./bootstrap");

window.Vue = require("vue");
import moment from "moment";
//import { Form, HasError, AlertError } from "vform";
Vue.component("pagination", require("laravel-vue-pagination"));

//gate.js
import Gate from "./Gate";
Vue.prototype.$gate = new Gate(window.user);

/* Sweet Alert */
import swal from "sweetalert2";
window.swal = swal;

const toast = swal.mixin({
    toast: true,
    position: "top-end",
    showConfirmButton: false,
    timer: 3000
});

window.toast = toast; /* global so we can use it in anywhere in the app */

//Vue.component(HasError.name, HasError);
//Vue.component(AlertError.name, AlertError);

/* Vue Routers -- We will write them to here */

import Vue from "vue";
import Form from "vform";

window.Form = Form; //to make it global

import VueRouter from "vue-router";
Vue.use(VueRouter);

import VueProgressBar from "vue-progressbar";
import { __esModule } from "bootstrap";

Vue.use(VueProgressBar, {
    color: "rgb(143, 255, 199)",
    failedColor: "red",
    height: "3px"
});

const routes = [
    {
        path: "/dashboard",
        component: require("./components/Dashboard.vue").default,
        name: "dashboard"
    },
    {
        path: "/profile",
        component: require("./components/Profile.vue").default,
        name: "profile"
    },
    {
        path: "/users",
        component: require("./components/Users.vue").default,
        name: "users"
    },
    //DEV
    {
        path: "/developer",
        component: require("./components/Developer.vue").default,
        name: "developer"
    },
    {
        path: "*",
        component: require("./components/NotFound.vue").default,
        name: "asterisk"
    },
    {
        path: "/invoice",
        component: require("./components/Invoice.vue").default,
        name: "invoice"
    }
];

const router = new VueRouter({
    mode: "history",
    linkActiveClass: "active",
    routes // short for `routes: routes`
});

//Global Filter -> use it anywhere in application
Vue.filter("upText", function(text) {
    return text.charAt(0).toUpperCase() + text.slice(1);
});

Vue.filter("myDate", function(created) {
    return moment(created).format("MMMM Do YYYY");
});

//let Fire = new Vue();
//window.Fire = Fire;
//window.Fire = new Vue();
window.Fire = new Vue();

/**
 * Next, we will create a fresh Vue application instance and attach it to
 * the page. Then, you may begin adding components to this application
 * or customize the JavaScript scaffolding to fit your unique needs.
 */

Vue.component(
    "example-component",
    require("./components/ExampleComponent.vue")
);

Vue.component("not-found", require("./components/NotFound.vue"));

Vue.component("invoice", require("./components/Invoice.vue"));

const app = new Vue({
    el: "#app",
    router,
    //search
    data: {
        search: ""
    },
    methods: {
        /*searchit() {
            console.log("app ok");
            Fire.$emit("searching");
            console.log(Fire.$emit("searching"));
        }*/

        //wait 2sec then send request
        searchit: _.debounce(() => {
            Fire.$emit("searching");
        }, 1000)
    }
});

//DEV
Vue.component("passport-clients", require("./components/passport/Clients.vue"));

Vue.component(
    "passport-authorized-clients",
    require("./components/passport/AuthorizedClients.vue")
);

Vue.component(
    "passport-personal-access-tokens",
    require("./components/passport/PersonalAccessTokens.vue")
);
