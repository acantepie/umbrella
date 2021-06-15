import AjaxUtils from "./AjaxUtils";
import Utils from "./Utils";

export default class BindUtils
{
    static enableToast(selector = '[data-toggle=toast]') {
        var els = document.querySelectorAll(selector)
        els.forEach((el) => {
            umbrella.Toast.show(
                el.getAttribute('data-type'),
                el.getAttribute('data-text'),
                el.getAttribute('data-title')
            )
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

    static enableXhrElement() {
        $('body').on('click', '[data-xhr]:not(form)', (e) => {
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

    static enableXhrForm() {
        $('body').on('submit', 'form[data-xhr]', (e) => {
            e.preventDefault();
            const $e = $(e.currentTarget);

            AjaxUtils.request({
                url: $e.data('xhr'),
                xhr_id: $e.data('xhr-id') || null,
                confirm: $e.data('confirm') || false,
                spinner: $e.data('spinner') || false,
                method: $e.attr('method') || 'post',
                data: Utils.create_formdata_with_files(e.currentTarget),
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