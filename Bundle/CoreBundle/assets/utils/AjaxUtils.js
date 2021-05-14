export default class AjaxUtils {

    static xhrPendingRegistryIds = [];

    static request(options = {}) {
        if ('xhr_id' in options && options['xhr_id']) {
            if (AjaxUtils.xhrPendingRegistryIds.includes(options['xhr_id'])) {
                console.warn(`Request prevented : request with id ${options['xhr_id']} is pending.`);
                return;
            } else {
                AjaxUtils.xhrPendingRegistryIds.push(options['xhr_id']);
            }
        }

        if ('data' in options && options['data'] instanceof FormData) {
            options['contentType'] = false;
            options['processData'] = false;
        }

        if ('spinner' in options && false !== options['spinner']) {
            umbrella.Spinner.show({text: options['spinner']});
        }

        options['success'] = (response) => {
            umbrella.jsResponseHandler.success(response);
        };
        options['error'] = (requestObject, error, errorThrown) => {
            umbrella.jsResponseHandler.error(requestObject, error, errorThrown);
        };
        options['complete'] = () => {

            if ('xhr_id' in options && options['xhr_id']) {
                AjaxUtils.xhrPendingRegistryIds = $.grep(AjaxUtils.xhrPendingRegistryIds, (id) => {
                    return id !== options['xhr_id'];
                });
            }

            umbrella.Spinner.hide();
        };

        // mark request with cutom headers to allow server to identify it
        if (!('headers' in options)) {
            options['headers'] = {};
        }
        options['headers']['xhr-request'] = 'js';

        if ('confirm' in options && false !== options['confirm']) {
            umbrella.ConfirmModal.show({
                'text': options['confirm'],
                'confirm': () => $.ajax(options)
            });
        } else {
            return $.ajax(options);
        }


    }

    static get(options = {}) {
        options['method'] = 'get';
        return AjaxUtils.request(options);
    }

    static post(options = {}) {
        options['method'] = 'post';
        return AjaxUtils.request(options);
    }
}