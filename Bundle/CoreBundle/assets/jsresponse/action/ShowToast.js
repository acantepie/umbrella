import JsResponseAction from "../JsResponseAction";

export default class ShowToast extends JsResponseAction {
    eval(params) {
        toastr.options = params;
        toastr[params['type']](params['text'], params['title']);
    }
}