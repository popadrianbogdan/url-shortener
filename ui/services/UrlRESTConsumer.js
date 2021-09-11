import axios from "axios";

export default {
    getUrls() {
        return axios.get('/api/v1/urls')
    },
    getUrl(id) {
        return axios.get(`/api/v1/urls/${id}`);
    },
    createUrl(data) {
        return axios.post(`/api/v1/urls`, data);
    },
    editUrl(url) {
        return axios.put(`/api/v1/urls/${url.id}`, url)
    },
    deleteUrl(id) {
        return axios.delete(`/api/v1/urls/${id}`)
    }
}