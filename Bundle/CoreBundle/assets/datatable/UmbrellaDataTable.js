import Utils from '../utils/Utils';
import DataTable from 'datatables.net';
import 'datatables.net-bs5';

import i18n from './DataTable.i18n.js';

import SelectPlugin from './plugin/SelectPlugin';
import RowDetailsPlugin from './plugin/RowDetailsPlugin';
import TreePlugin from './plugin/TreePlugin';
import AjaxUtils from '../utils/AjaxUtils';

export default class UmbrellaDataTable extends HTMLElement {

    constructor() {
        super()

        this.reloadTimer = null
        this.datatable = null

        this.form = this.querySelector('form')
        this.table = this.querySelector('table.js-datatable')
        this.thead = this.table.querySelector('thead')
        this.tbody = this.table.querySelector('tbody')

        this.options = JSON.parse(this.dataset.options)
    }

    connectedCallback() {
        if (umbrella.LANG in i18n) {
            this.options['language'] = i18n[umbrella.LANG];
        }

        this.options['processing'] = true
        this.options['ajax']['data'] = this._ajaxData.bind(this)
        this.options['ajax']['error'] = this._ajaxError.bind(this)
        this.options['drawCallback'] = this._drawCallback.bind(this)

        console.log(this.options)
        this.datatable = new DataTable(this.table, this.options)
        this._bindActions()
        this._bindForm()

        // Plugins
        if (this.options['selectable']) {
            this.registerPlugin(new SelectPlugin())
        }

        if (this.options['tree']) {
            this.registerPlugin(new TreePlugin(this.options['tree']))
        }

        this.registerPlugin(new RowDetailsPlugin())
    }

    disconnectedCallback() {
        this.datatable.destroy()
        clearTimeout(this.reloadTimer)
    }

    _bindActions() {

        this.querySelectorAll('[data-send-state][data-dt-xhr]').forEach(stateAction => {
            stateAction.addEventListener('click', e => {
                e.preventDefault()
                AjaxUtils.requestWithElement(stateAction, {
                    url: stateAction.dataset.dtXhr,
                    data: {
                        state: this.getState()
                    }
                })
            })
        })
    }

    _bindForm() {
        if (!this.form) {
            return
        }

        if (this.form.dataset.submit === 'auto') { // auto submit
            this.form.querySelectorAll('select, input[type=checkbox], input[type=radio]').forEach(e => {
                e.addEventListener('change', () => this.reloadAfter(100))
            })

            this.form.querySelectorAll('input[type=search], input[type=text]').forEach(e => {
                e.addEventListener('change', () => this.reloadAfter(200))
                e.addEventListener('paste', () => this.reloadAfter(200))
                e.addEventListener('input', () => this.reloadAfter(200))
            })

        } else { // manual submit
            this.form.addEventListener('submit', (e) => {
                e.preventDefault()
                this.reload()
            })
        }
    }

    _ajaxData(data) {
        // avoid send useless data
        delete data['columns'];
        delete data['search'];

        // add dataTable id
        data['_dtid'] = this.id

        // add form data
        if (this.form) {
            data = {...data, ...Utils.objectify_formdata(new FormData(this.form))}
        }

        return data;
    }
    _ajaxError(requestObject, error, errorThrown) {
        if (requestObject.status === 401) {
            this.error(umbrella.Translator.trans('disconnected_error'))
        } else if (requestObject.responseJSON && requestObject.responseJSON.error) {
            this.error(requestObject.responseJSON.error);
        } else {
            this.error(umbrella.Translator.trans('loading_data_error'))
        }
    }

    _drawCallback() {
        this.tbody.querySelectorAll('[data-bs-toggle=tooltip]').forEach(e => new bootstrap.Tooltip(e))
    }

    _getCurrentState() {
        let state = this.datatable.ajax.params()
        state['count'] = {
            'page' : this.datatable.rows().count(),
            'total' : this.datatable.page.info().recordsTotal,
        }
        return state
    }

    // --- Api --- //

    getState() {
        return this._getCurrentState()
    }

    registerPlugin(plugin) {
        plugin.configure(this)
    }

    reload(paging = true) {
        this.datatable.draw(paging);
    }

    reloadAfter(wait = 0, paging = true) {
        clearTimeout(this.reloadTimer)
        this.reloadTimer = setTimeout(() => this.reload(paging), wait)
    }

    error(error) {
        this.tbody.innerHTML = `<tr><td class="text-danger text-center" colspan="100%">${error}</td></tr>`
    }

}
