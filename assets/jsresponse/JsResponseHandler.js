export default class JsResponseHandler {

    constructor() {
        this.actionRegistry = {};
        this.errorHandler = null;
    }

    // Actions registry

    registerAction(id, callback) {
        if (typeof(callback) === 'function') {
            this.actionRegistry[id] = callback;
            return;
        }

        console.error(`Can't register action ${callback}, callback must be a function`);
    }

    removeAction(id) {
        delete this.actionRegistry[id];
    }

    clearActions() {
        this.actionRegistry = {};
    }

    // ErrorHandler

    setErrorHandler(callback) {
        if (typeof(callback) === 'function') {
            this.errorHandler = callback;
            return;
        }

        console.error(`Can't set ${callback} as error handler, callback must be a function`);
    }

    // Handle jsResponse

    success(response) {
        if (!Array.isArray(response)) {
            console.error('[JsReponseHandler] invalid response, expected json array have :', response);
            return;
        }

        for (const message of response) {
            if (!message.hasOwnProperty('action')) {
                console.error('[JsReponseHandler] missing action property on message :', message);
                continue;
            }

            if (!message.hasOwnProperty('params')) {
                console.error('[JsReponseHandler] missing params property on message :', message);
                continue;
            }

            if (!this.actionRegistry.hasOwnProperty(message.action)) {
                console.error(`[JsReponseHandler] Action "${message.action}" not found on regsitry. have you register it using JsResponseHandler.jsResponseHandler.registerAction(new MyAction()) ?`);
                continue;
            }

            this.actionRegistry[message.action](message.params);
        }
    }

    error(requestObject, error, errorThrown)
    {
        if (null !== this.errorHandler) {
            this.errorHandler(requestObject, error, errorThrown);
        }
    }
}
