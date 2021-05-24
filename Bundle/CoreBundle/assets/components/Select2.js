import 'select2/dist/js/select2.full';
import 'select2/dist/js/i18n/fr';
import mustache from "mustache";

export default class select2 extends HTMLSelectElement {
    constructor() {
        super();
        this.render()
    }

    _getOptions() {
        const formOptions = JSON.parse(this.getAttribute('data-options'))

        let select2Options = {
            language: umbrella.LANG,
            placeholder: formOptions['placeholder'],
            allowClear: formOptions['allow_clear'],
            minimumInputLength: formOptions['min_search_length'],
        }

        // ajax loading
        if (formOptions['autocomplete_url']) {
            select2Options['ajax'] = {
                url: formOptions['autocomplete_url'],
                dataType: 'json',
                delay: 250,
                data: function (params) {
                    return {'query': params.term, 'page': params.page};
                },
                cache: true
            }
        }

        // template renderer
        let template = null;

        if (formOptions['template_selector']) {
            const templateEl = document.querySelector(formOptions['template_selector'])
            if (templateEl) {
                template = templateEl.innerHTML
            } else {
                console.error("[Select2.js] No template found with selector " + formOptions['template_selector']);
            }
        } else if (formOptions['template']) {
            template = formOptions['template']
        }

        if (template) {
            mustache.tags = [ '[[', ']]' ];

            select2Options['templateResult'] = (state) => {
                if (!state.id) {
                    return state.text;
                }

                let data = state;

                // add data retrieve from vanilla option element
                if (state.element) {
                    const exposedData = $(state.element).data() || {};
                    data = {...exposedData, ...data}
                }

                return $('<span>' + mustache.render(template, data) + '</span>');
            };
        }

        return select2Options;
    }

    render() {
        $(this).select2(this._getOptions());
    }
}