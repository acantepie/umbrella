import JsResponseAction from '../JsResponseAction';

export default class UpdateHtml extends JsResponseAction {
    eval(params) {
        const $view = $(params.selector);
        $view.html(params.value);
    }

}