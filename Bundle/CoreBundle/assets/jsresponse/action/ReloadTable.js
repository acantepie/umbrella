import JsResponseAction from "../JsResponseAction";

export default class ReloadTable extends JsResponseAction {

    eval(params) {
        let selector = '';
        if (params.ids && params.ids.length > 0) {
            selector = params.ids.map((id) => 'umbrella-datatable#' + id).join(', ');
        } else {
            selector = 'umbrella-datatable';
        }

        for (let element of document.querySelectorAll(selector)) {
            element.reload();
        }
    }
}