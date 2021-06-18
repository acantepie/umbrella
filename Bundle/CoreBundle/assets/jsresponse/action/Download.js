import JsResponseAction from '../JsResponseAction';

export default class Download extends JsResponseAction {
    eval(params) {
        const link = document.createElement('a')

        link.href = URL.createObjectURL(new Blob([params.content]));

        if (params.filename) {
            link.download = params.filename
        }

        link.click()
    }
}