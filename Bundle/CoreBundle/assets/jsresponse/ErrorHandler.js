export default class ErrorHandler {
    handle(requestObject, error, errorThrown) {
        if (requestObject.status === 401) {
            umbrella.Toast.alertWarning(umbrella.Translator.trans('disconnected_error'));

        } else if (requestObject.status === 404) {
            umbrella.Toast.alertWarning('404 - ' + umbrella.Translator.trans('unable_to_contact_server'));

        } else {
            umbrella.Toast.alertError(umbrella.Translator.trans('error_occured'));
        }
    }
}
