import JsResponseAction from '../JsResponseAction';

export default class Reload extends JsResponseAction {
    eval(params) {
        window.location.href = window.location.href.split('#')[0];
    }
}