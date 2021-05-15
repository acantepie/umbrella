import JsResponseAction from "../JsResponseAction";

export default class WebComponent extends JsResponseAction {

    eval(params) {
        for (let element of document.querySelectorAll(params.selector)) {
            element[params.method](...params.method_params);
        }
    }
}