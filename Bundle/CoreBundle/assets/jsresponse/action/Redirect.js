import JsResponseAction from '../JsResponseAction';

export default class Redirect extends JsResponseAction {
    eval(params) {
        window.location = params.value;
    }
}