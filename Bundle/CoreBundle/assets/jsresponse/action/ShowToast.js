import JsResponseAction from "../JsResponseAction";

export default class ShowToast extends JsResponseAction {
    eval(params) {
        umbrella.Toast.renderFromHTML(params['value']);
    }
}