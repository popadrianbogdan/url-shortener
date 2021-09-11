import Vue from "vue";

export default {
    simpleError(msg) {
        let message = `
        <div class="alert alert-danger text-center" role="alert">
          ${msg}
        </div>
        `;
        let options = {
            html: true,
            okText: "OK",
            animation: "fade"
        };

        Vue.dialog.alert(message, options);
    },
    simpleSuccess(msg) {
        let message = `
        <div class="alert alert-success text-center" role="alert">
          ${msg}
        </div>
        `;
        let options = {
            html: true,
            okText: "Close",
            animation: "fade"
        };

        Vue.dialog.alert(message, options);
    }
}