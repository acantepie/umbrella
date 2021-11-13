import 'select2/dist/js/select2.full';
import 'select2/dist/js/i18n/fr';
import mustache from 'mustache'

export default class select2 extends HTMLSelectElement {

    constructor() {
        super()

        mustache.tags = ['[[', ']]']

        this.$el = $(this)
        this.reset = this.reset.bind(this)
    }

    connectedCallback() {
        const options = JSON.parse(this.dataset.options)
        let select2_options = options['select2'] || {}
        select2_options['language'] = umbrella.LANG

        // --- Build options --- //
        if (options['autocomplete_url']) {
            select2_options['ajax'] = {
                url: options['autocomplete_url'],
                dataType: 'json',
                delay: 250,
                cache: true,
                data: function (params) {
                    return {'q': params.term, 'page': params.page || 1}
                },
                processResults: (data, params) => {
                    let response = {
                        results: []
                    };

                    params.page = params.page || 1

                    if (Array.isArray(data)) {
                        response.results = data;

                    } else if (typeof data === 'object') {

                        if ('results' in data) {
                            response.results = data.results
                        } else {
                            console.error('[Select2.js] Invalid response format : ', data)
                            return response
                        }

                        if ('more' in data) {
                            response.pagination = {more: data.more}
                        }

                    } else {
                        console.error('[Select2.js] Invalid response format : ', data)
                    }

                    return response;
                }
            }
        }

        let template = null

        if (options['template_selector']) {
            const templateEl = document.querySelector(options['template_selector'])
            if (templateEl) {
                template = templateEl.innerHTML
            } else {
                console.error('[Select2.js] No template found with selector ' + options['template_selector'])
            }
        } else if (options['template']) {
            template = options['template']
        }

        if (template) {
            select2_options['templateResult'] = (state) => {
                if (!state.id) {
                    return state.text
                }

                let data = state

                // add data retrieve from vanilla option element
                if (state.element && state.element.dataset.json) {
                    const exposedData = JSON.parse(state.element.dataset.json)
                    data = {...exposedData, ...data}
                }

                return $('<span>' + mustache.render(template, data) + '</span>')
            }
        }


        // --- Init select2 --- //
        this.$el.select2(select2_options)

        // --- Custom event --- //
        // Hack - reset to default value if form is resseted
        this.closest('form').addEventListener('reset', this.reset)
    }


    selectValue(val) {
        this.$el.val(val)
        this.update()
    }

    selectAll() {
        this.each((option) => {
            option.selected = true
        })
        this.update()
    }

    unselectAll() {
        this.each((option) => {
            option.selected = false
        })
        this.update()
    }

    each(fn) {
        for (let option of this.options) {
            fn(option)
        }
    }

    getSelectedValue() {
        return this.$el.val()
    }

    getSelectedData() {
        return this.$el.select2('data')
    }

    update() {
        this.$el.trigger('change.select2')
    }

    reset() {
        this.$el.find('option').prop('selected', function () {
            return this.defaultSelected
        })
        this.$el.trigger('change.select2')
    }

    open() {
        this.$el.select2('open')
    }

    close() {
        this.$el.select2('close')
    }
}