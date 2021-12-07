import AjaxUtils from './AjaxUtils';

export default class BindUtils {

    static enableToast(selector = '[data-toggle=toast]') {
        document.querySelectorAll(selector).forEach((el) => {
            umbrella.Toast.show(
                el.getAttribute('data-type'),
                el.getAttribute('data-text'),
                el.getAttribute('data-title')
            )
        })
    }

    static enableTooltip(selector = '[data-bs-toggle=tooltip]') {
        document.querySelectorAll(selector).forEach((el) => {
            new bootstrap.Tooltip(el)
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
        $('body').on('submit', 'form[data-xhr]', (form) => {
            form.preventDefault();
            const $form = $(form.currentTarget);

            AjaxUtils.request({
                url: $form.data('xhr'),
                xhr_id: $form.data('xhr-id') || null,
                confirm: $form.data('confirm') || false,
                spinner: $form.data('spinner') || false,
                method: $form.attr('method') || 'post',
                data: new FormData(form.currentTarget),
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