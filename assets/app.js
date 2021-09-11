import './scss/app.scss';
import Vue from 'vue';
import Layout from '../ui/components/layout';
import router from "../ui/router";
import VueLocalStorage from "vue-ls";
import VueJsDialog from "vuejs-dialog";
import { ValidationProvider, ValidationObserver, extend } from "vee-validate";
import {required, email, length} from 'vee-validate/dist/rules';
import 'vuejs-dialog/dist/vuejs-dialog.min.css';

import interceptorsSetup from '../ui/configs/RouterInterceptoSettings'

interceptorsSetup()

Vue.use(VueLocalStorage);
Vue.use(VueJsDialog);

Vue.component('ValidationProvider', ValidationProvider);
Vue.component('ValidationObserver', ValidationObserver);

extend('required', {
    ...required,
    message: 'This field is required'
});

extend('email', email);
extend('length', length)

new Vue({
    router,
    components: { Layout },
    template: "<Layout />"
}).$mount("#vue-app")


