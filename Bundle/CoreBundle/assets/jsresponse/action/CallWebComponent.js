import JsResponseAction from '../JsResponseAction';

export default class CallWebComponent extends JsResponseAction {

    eval(params) {
        for (let element of document.querySelectorAll(params.selector)) {
            if (typeof element[params.method] === 'undefined') {
                console.warn('Function ' + params.method + '() doesn\'t exist for custom element ', element)
            } else {
                element[params.method](...params.method_params)
            }
        }
    }
}