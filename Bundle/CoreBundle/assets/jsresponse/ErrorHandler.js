export default class ErrorHandler {
    handle(requestObject, error, errorThrown) {
        if (requestObject.status === 401) {
            toastr.warning(Translator.trans('disconnected_error'));

        } else if (requestObject.status === 404) {
            toastr.warning('404 - ' + Translator.trans('unable_to_contact_server'));

        } else {
            toastr.error(Translator.trans('error_occured'));
        }
    }
}
