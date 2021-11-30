import TomSelect from 'tom-select';
import mustache from 'mustache';
import {min} from '@popperjs/core/lib/utils/math';

export default class UmbrellaSelect extends HTMLSelectElement {

    constructor() {
        super()
        this.template = null
        this.loadUrl = null

        mustache.tags = ['[[', ']]']
    }

    connectedCallback() {
        this.selectOptions = JSON.parse(this.dataset.options)
        this._loadTemplateOption()

        let tomSelectOptions = this.selectOptions['tom'];
        tomSelectOptions['plugins'] = []
        tomSelectOptions['render'] = {
            'option': this._renderOption.bind(this),
            'no_results': this._renderNoResults.bind(this),

        }

        if (this.selectOptions['load_url']) {

            // enable virtual scroll - FIXME not working plugins seems bugged
            if (this.selectOptions['page_length'] > 0) {
                tomSelectOptions['plugins'].push('virtual_scroll')
                tomSelectOptions['firstUrl'] = (query) => this._getUrl(query, 1)
                tomSelectOptions['load'] = this._loadNext.bind(this)
                tomSelectOptions['maxOptions'] = this.selectOptions['page_length']

            } else {
                tomSelectOptions['load'] = this._load.bind(this)
            }
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

    _load(query, callback) {
        fetch(this._getUrl(query))
            .then(response => response.json())
            .then(json => callback(json))
            .catch(()=> callback());
    }

    _loadNext(query, callback) {
        const url = this.tomSelect.getUrl(query);

        const currentPage = Math.max(parseInt(new URL(url).searchParams.get('p')), 1)

        fetch(url)
            .then(response => response.json())
            .then(json => {
                // if result returned are equals to page === length => there is an other page
                if (json.length === this.selectOptions['page_length']) {
                    this.tomSelect.setNextUrl(query, this._getUrl(query, currentPage + 1))
                }
                callback(json);
            }).catch(()=> callback());
    }

    _getUrl(query, page = null) {
        const url = new URL(this.selectOptions['load_url'])

        url.searchParams.set('q', query)

        if (null !== page) {
            url.searchParams.set('p', page)
        }

        return url.toString();
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