import mustache from 'mustache';
import Utils from "../utils/Utils";

// don't use this.options on HTMLSelectElement elements
export default class Select2 extends HTMLSelectElement {

    constructor() {
        super();

        mustache.tags = [ '[[', ']]' ];

        this.$view = $(this);

        let data_options = this.$view.data('options');

        this._options = data_options ? JSON.parse(Utils.decode_html(data_options)) : {};
        this.s2_options = this._options['select2'] ? this._options['select2'] : {};
        this.s2_options['language'] = LANG;

        // templating
        let mustacheTemplate = null;

        if (this._options['template_selector']) {
            const $template = $(this._options['template_selector']);
            if ($template.length === 0) {
                console.error("No template found with selector " + this._options['template_selector']);
            } else {
                mustacheTemplate = $template.html();
            }
        }

        if (this._options['template_html']) {
            mustacheTemplate = this._options['template_html'];
        }

        if (mustacheTemplate) {
            this.s2_options['templateResult'] = (state) => {
                if (!state.id) {
                    return state.text;
                }

                let data = state;

                // add data retrieve from vanilla option element
                if (state.element) {
                    const exposedData = $(state.element).data('json') || {};
                    data = {...exposedData,...data}
                }

                return $('<span>' + mustache.render(mustacheTemplate, data) + '</span>');

            };
        }
    }

    connectedCallback() {
        this.$view.select2(this.s2_options);

        // Hack - reset to default value if form is resseted
        this.$view.closest('form').on('reset', (e) => {

            this.$view.find('option').prop('selected', function () {
                return this.defaultSelected;
            });
            this.$view.trigger('change.select2');
        });
    }

    disconnectedCallback() {
        //this.$view.select2('destroy');
    }

    open() {
        this.$view.select2('open');
    }
}