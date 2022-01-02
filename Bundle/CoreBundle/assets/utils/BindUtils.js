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
            AjaxUtils.requestWithElement(e.currentTarget)
        });

        $('body').on('submit', 'form[data-xhr]', (e) => {
            e.preventDefault();
            AjaxUtils.requestWithElement(e.currentTarget)
        });
    }

    static enableAll() {
        this.enableToast()
        this.enableTooltip()
        this.enableXhrElement()
    }
}
