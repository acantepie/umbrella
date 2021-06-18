import JsResponseAction from './JsResponseAction';
import Callback from './action/Callback';
import ErrorHandler from './ErrorHandler';

export default class JsResponseHandler {

    constructor() {
        this.actionRegistry = {};
        this.errorHandler = new ErrorHandler();
    }

    // Actions registry

    registerAction(id, obj) {
        if (obj instanceof JsResponseAction) {
            this.actionRegistry[id] = obj;
            return;
        }

        if (typeof(obj) === 'function') {
            this.actionRegistry[id] = new Callback(obj);
            return;
        }

        console.error(`Can't register action ${obj}, obj must be a function or extends JsResponseAction class`);
    }

    removeAction(id) {
        delete this.actionRegistry[id];
    }

    clearActions() {
        this.actionRegistry = {};
    }

    // ErrorHandler

    setErrorHandler(obj) {
        if (obj instanceof ErrorHandler) {
            this.errorHandler = obj;
            return;
        }

        console.error(`Can't set ${obj} as error handler, obj must extends ErrorHandler class`);
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

            this.actionRegistry[message.action].eval(message.params);
        }
    }

    error(requestObject, error, errorThrown)
    {
        this.errorHandler.handle(requestObject, error, errorThrown);
    }
}
