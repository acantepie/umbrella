import JsResponseAction from '../JsResponseAction';

export default class Eval extends JsResponseAction {
    eval(params) {
        eval(params.value);
    }
}