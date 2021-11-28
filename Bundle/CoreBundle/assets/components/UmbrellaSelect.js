import TomSelect from 'tom-select';
import mustache from 'mustache';

export default class UmbrellaSelect extends HTMLSelectElement {

    constructor() {
        super()
        this.template = null

        mustache.tags = ['[[', ']]']
    }

    connectedCallback() {
        this.selectOptions = JSON.parse(this.dataset.options)
        this._loadTemplateOption()

        let tomSelectOptions = this.selectOptions['tom'];
        tomSelectOptions['render'] = {
            'option': this._renderOption.bind(this),
            'no_results': this._renderNoResults.bind(this)
        }

        if (this.selectOptions['load_url']) {
            tomSelectOptions['load'] = this._loadRemoteData.bind(this)
        }

        this.tomSelect = new TomSelect(this, tomSelectOptions)
    }

    _loadTemplateOption() {
        if (this.selectOptions['template_selector']) {
            const templateEl = document.querySelector(this.selectOptions['template_selector'])
            if (null === templateEl) {
                throw new Error('[UmbrellaSelect] No template found with selector ' + this.selectOptions['template_selector'])
            }
            this.template = templateEl.innerHTML

        } else if (this.selectOptions['template']) {
            this.template = this.selectOptions['template']
        }
    }

    _renderOption(data, escape) {
        if (!data.value || null === this.template) {
            return '<div>' + escape(data.text) + '</div>'
        }

        let vars = data.json ? JSON.parse(data.json) : {}
        vars = {...vars, ...data}

        return '<div>' + mustache.render(this.template, vars) + '</div>'
    }

    _renderNoResults() {
        return '<div class="no-results">' + umbrella.Translator.trans('no_results') + '</div>';
    }

    _loadRemoteData(query, callback) {

        $.ajax({
            url: this.selectOptions['load_url'],
            data: {
                q: query
            },
            success: (data) => {
                callback(data)
            }
        })
    }

    // Api

    addOption(data, user_created = false) {
        this.tomSelect.addOption(data, user_created)
    }

    addOptions(data, user_created = false) {
        this.tomSelect.addOptions(data, user_created)
    }

    removeOption(value) {
        this.tomSelect.removeOption(value)
    }

    clear() {
        this.tomSelect.clear()
    }

    getOption(value) {
        return this.tomSelect.getOption(value)
    }

    open() {
        this.tomSelect.open()
    }

    close() {
        this.tomSelect.close()
    }

    getValue() {
        return this.tomSelect.getValue()
    }

    setValue(value) {
        this.tomSelect.setValue(value)
    }
}