import NotificationServiceInterface from "varie/lib/plugins/notifications/NotificationServiceInterface";

export default class CaptureErrors {

    public responseError(error) {
        let message = "";
        let response = error.response;

        let notifications = $app.make<NotificationServiceInterface>('$notifications');

        switch(response.status) {
            case 422 :
                let errors = response.data.errors;
                for(let error in errors) {
                    message += errors[error] + "<br>";
                }
                notifications.showError(message, 'Validation Error');
                break;
            default :
                notifications.showError(error.response.data);
                break;
        }
        return Promise.reject(error);
    }
}
