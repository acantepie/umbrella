import JsResponseAction from '../JsResponseAction';

export default class ShowToast extends JsResponseAction {
    eval(params) {
        umbrella.Toast.show(params['type'], params['text'], params['title'], params['options']);
    }
}