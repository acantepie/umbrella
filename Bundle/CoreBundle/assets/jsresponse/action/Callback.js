import JsResponseAction from '../JsResponseAction';

export default class Callback extends JsResponseAction {

    constructor(callback) {
        super();
        this.callback = callback;
    }

    eval(params) {
        this.callback(params);
    }
}