import NotificationService from "../services/NotificationService";

export default {
    methods: {
        simpleError(msg) {
            NotificationService.simpleError(msg)
        },
        simpleSuccess(msg) {
            NotificationService.simpleSuccess(msg)
        }
    }
}