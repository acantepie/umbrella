import AjaxUtils from "./AjaxUtils";

export default class BindUtils
{
    static enableToast(container = null, selector = '.toast-container .toast') {
        container = container || document

        var els = document.querySelectorAll(selector)
        els.forEach((el) => {
            umbrella.Toast.show(el)
        })
    }

    static enableTooltip(container = null, selector = '[data-bs-toggle=tooltip]') {
        container = container || document

        var els = document.querySelectorAll(selector)
        els.forEach((el) => {
            new bootstrap.Tooltip(el, {
                container: 'body',
                trigger: el.getAttribute('data-bs-trigger') || 'hover focus'
            })
        })
    }

    // if you don't want your link was bind : use class no-bind
    static enableXhrElement() {
        $('body').on('click', '[data-xhr]:not(form):not(.no-bind)', (e) => {
            e.preventDefault();
            const $e = $(e.currentTarget);

            AjaxUtils.get({
                url: $e.data('xhr'),
                xhr_id: $e.data('xhr-id') || null,
                confirm: $e.data('confirm') || false,
                spinner: $e.data('spinner') || false
            });
        });
    }

    // if you don't want your form was bind : use class no-bind
    static enableXhrForm() {
        $('body').on('submit', 'form[data-xhr]:not(.no-bind)', (e) => {
            e.preventDefault();
            const $e = $(e.currentTarget);

            AjaxUtils.request({
                url: $e.data('xhr'),
                xhr_id: $e.data('xhr-id') || null,
                confirm: $e.data('confirm') || false,
                spinner: $e.data('spinner') || false,
                method: $e.attr('method') || 'post',
                data: $e.serializeFormToFormData(),
            })
        });
    }

    static enableAll() {
        this.enableToast()
        this.enableTooltip()
        this.enableXhrElement()
        this.enableXhrForm()
    }
}