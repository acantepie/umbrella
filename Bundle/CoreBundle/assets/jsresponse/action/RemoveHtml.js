import JsResponseAction from '../JsResponseAction';

export default class RemoveHtml extends JsResponseAction {
    eval(params) {
        $(params.selector).remove();
    }
}