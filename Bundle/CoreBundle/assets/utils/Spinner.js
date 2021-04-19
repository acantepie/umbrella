export default class Spinner {

    static template = '<div id="spinner">' +
        '<div class="bouncing-loader-wrapper">' +
        '<div class="bouncing-loader">' +
        '<div></div>' +
        '<div></div>' +
        '<div></div>' +
        '</div>' +
        '</div>' +
        '</div>';


    static show() {
        Spinner.hide();
        $('body').after(Spinner.template);
    }

    static hide() {
        const $spinner = $('#spinner');
        if ($spinner.length) {
            $spinner.remove();
        }
    }
}
