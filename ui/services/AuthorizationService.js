import axios from "axios";
import LocalStorageService from "./LocalStorageService";

export default {
    login(email, password) {
        return axios.post('api/v1/sessions', {
            email: email,
            password: password
        })
    },

    async logout() {
        const promise = axios.delete("/api/v1/sessions", {
            headers: {
                'X-Auth': LocalStorageService.getAuthToken()
            }
        });
        this.clearAllAuthTokens();
        return promise;
    },

    getMe() {
        return axios.get("api/v1/sessions/me")
    },

    register(email, password) {
        return axios.post('api/v1/user', {
            email: email,
            password: password
        })
    },

    clearAllAuthTokens() {
        LocalStorageService.clearAuthToken();
        LocalStorageService.clearUserEmail();
    }
}