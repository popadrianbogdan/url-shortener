import Vue from "vue";

export default class HttpErrorHandler {
    constructor(err) {
        this.error = err;
        this.handleTheError();
    }

    handleTheError() {
        let message = '<h2 class="alert alert-danger">Something went wrong</h2>';
        let options = {
            html: true,
            okText: "OK ",
            animation: "fade"
        };

        switch (this.error.status) {
            case 500:
            default:
                if (this.error.statusText) {
                    message += `<div>${this.error.statusText}</div>`;
                }

                Vue.dialog.alert(message, options);
                break;
        }
    }
}