import Vue from "vue";
import * as constants from "../constants";

export default {
    setAuthToken(token) {
        Vue.ls.set("token", token, constants.AUTH_TOKEN_EXPIRATION);
    },
    getAuthToken() {
        return Vue.ls.get("token");
    },
    clearAuthToken() {
        Vue.ls.remove("token");
    },

    setUserEmail(userEmail) {
        return Vue.ls.set("userEmail", userEmail);
    },
    getUserEmail() {
        return Vue.ls.get("userEmail");
    },
    clearUserEmail() {
        Vue.ls.remove("userEmail");
    },
}