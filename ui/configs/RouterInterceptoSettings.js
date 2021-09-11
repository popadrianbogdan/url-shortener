import Vue from "vue";
import axios from "axios";
import router from "../router";
import LocalStorageService from "../services/LocalStorageService";
import AuthorizationService from "../services/AuthorizationService";
import HttpErrorHandler from "../services/HttpErrorHandler";
import * as constants from "../constants";
import NotificationService from "../services/NotificationService";

Vue.use(router);

export default function interceptorsSetup() {
    axios.interceptors.request.use(function(config) {
        const token = LocalStorageService.getAuthToken()

        if(token) {
            config.headers[constants.AUTH_HEADER] = token
        }

        return config;
    }, function(err) {
        return Promise.reject(err);
    });

    axios.interceptors.response.use(function (response) {
        return response
    }, function(err) {
        if (err.response.status === 401) {
            AuthorizationService.clearAllAuthTokens();

            return NotificationService.simpleError(err.response.statusText);
        }

        if (err.response.status === 403) {
            router.push({ name: "403" });
            return;
        }

        if (err.response.status >= 409) {
            new HttpErrorHandler(err.response);
        }
        return Promise.reject(err);
    })

    router.beforeEach(async (to, from, next) => {
        const token = LocalStorageService.getAuthToken();

        let promises = [];
        if (token) {
            LocalStorageService.setAuthToken(token);

            if (!LocalStorageService.getUserEmail()) {
                const authPromise = AuthorizationService.getMe().then(
                    response => {
                        LocalStorageService.setUserEmail(response.data.email);
                    },
                    response => {
                        if (response.status === 401) {
                            LocalStorageService.clearAuthToken();
                        }
                    }
                );
                promises.push(authPromise);
            }
        }

        if (promises.length) {
            return Promise.all(promises).then(values => {
                checkAuth(to, next);
            });
        } else {
            checkAuth(to, next);
        }
    })
}

function checkAuth(to, next) {
    if (
        to.matched.some(record => record.meta.requiresAuth) &&
        !LocalStorageService.getAuthToken()
    ) {
        next({
            path: "/login",
            query: { redirect: to.fullPath }
        });
        return;
    }
    next()
}