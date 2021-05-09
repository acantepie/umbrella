import AjaxUtils from "../utils/AjaxUtils";
import BindUtils from "../utils/BindUtils"

import i18n from "./DataTable.i18n.js";

export default class DataTable extends HTMLElement {

    constructor() {
        super();
        this.$view = $(this);

        this.$table = this.$view.find('table.datatable');
        this.$tableBody = this.$table.find('tbody');

        this.options = this.$view.data('options');
        this.table = null;
        this.toolbar = this.querySelector('umbrella-toolbar');
        this.timer = null;

        this._buildOptions();
    }

    connectedCallback() {
        this.table = this.$table.DataTable(this.options);
        this._startAutoReload(this.options['poll_interval']);

        if (this.toolbar.addEventListener('tb:change', () => {
            this.reload();
        })) ;

        this.$view.on('click', '.js-reset-filter', (e) => {
            e.preventDefault();

            if (this.toolbar) {
                this.toolbar.reset();
            }
        });

        // row re-order
        if (this.options['rowReorder']) {
            this.table.on('row-reorder', (e, details, edit) => this._rowReorder(e, details, edit));
        }

        // row select
        this.table.on('click', 'thead .js-action-select', (e) => {
            e.preventDefault();
            let $target = $(e.currentTarget);
            let $checkboxes = this.$table.find('tbody tr td.js-select input[type=checkbox]');
            $checkboxes.prop('checked', $target.data('filter') === 'all');
            $checkboxes.trigger('change');
        });

        this.$tableBody.on('change', 'td.js-select input[type=checkbox]', (e) => {
            let $target = $(e.currentTarget);
            let $tr = $target.closest('tr');
            if ($target.prop('checked')) {
                $tr.addClass('selected');
            } else {
                $tr.removeClass('selected');
            }
        });

        this.$view.on('click', '[data-export]', (e) => {
            e.preventDefault();
            e.stopPropagation();
            this._export($(e.currentTarget));
        });
    }

    disconnectedCallback() {
        this.table.destroy();
        this._stopAutoReload();
    }

    _buildOptions() {
        if (LANG in i18n) {
            this.options['language'] = i18n[LANG];
        }

        this.options['ajax']['data'] = (data) => this._handleData(data);
        this.options['ajax']['error'] = (requestObject, error, errorThrown) => this._handleError(requestObject, error, errorThrown);

        this.options['preDrawCallback'] = (settings) => this._preDrawCallback();
        this.options['drawCallback'] = (settings) => this._drawCallback();
    }

    // ----- DataTable Callback ----- //

    _handleData(data, addFormData = true) {
        // avoid send useless data
        delete data['columns'];
        delete data['search'];

        const toolbarData = this.toolbar ? this.toolbar.getData() : {};

        return {...data, ...this.options['ajax_data'], ...toolbarData}; // pass data from options
    }

    _handleError(requestObject, error, errorThrown) {
        if (requestObject.status === 401) {
            this.displayError(Translator.trans('disconnected_error'));
        } else {

            if (requestObject.responseJSON && requestObject.responseJSON.error) {
                this.displayError(requestObject.responseJSON.error);
            } else {
                this.displayError();
            }

        }

        this._stopAutoReload();
    }

    _preDrawCallback() {
    }

    _drawCallback() {
        BindUtils.enableTooltip(this.$tableBody[0])

        if (this.options['tree']) {
            this._drawTree();
        }
    }

    // code below sucks ...
    _drawTree() {
        if (!this.options['tree']) {
            return;
        }

        // Retreive expanded nodes list from old Tree
        const expandedNodesId = [];
        if (this.$table.data('treetable') && this.$table.data('treetable').nodes) {

            for (const node of this.$table.data('treetable').nodes) {
                if (node.row.hasClass('expanded')) {
                    expandedNodesId.push(node.id);
                }
            }
        }

        this.$table.treetable({
            stringExpand: '',
            stringCollapse: '',
            expandable: true,
            clickableNodeNames: false,
            expanderTemplate: '<a href="#"><i class="mdi"></i></a>',
            initialState: this.options['tree_state']
        }, true);

        // Restore previously extanded nodes
        for (let nodeId of expandedNodesId) {
            try {
                this.$table.treetable('expandNode', nodeId);
            } catch (error) {
            }
        }
    }

    // ----- Autoreload ----- //

    _startAutoReload(pollInterval) {
        this.pollInterval = pollInterval;
        if (this.pollInterval > 0) {
            this.__autoReload();
        }
    }

    __autoReload() {
        if (this.pollInterval > 0) {
            this.timer = setTimeout(() => {
                this.reload(false);
                this.__autoReload();
            }, this.pollInterval * 1000);
        }
    }

    _stopAutoReload() {
        this.pollInterval = null;
        if (this.timer) {
            clearTimeout(this.timer);
        }
    }

    // ----- Extra ----- //

    _rowReorder(e, details, edit) {

        let changes = [];
        for (let i = 0, ien = details.length; i < ien; i++) {
            changes.push({
                id: details[i].node.getAttribute('data-id'),
                new: details[i].newPosition,
                old: details[i].oldPosition
            });
        }

        if (changes.length > 0) {
            AjaxUtils.get({
                xhr_id: 'tb-order',
                url: this.options['rowReorder']['url'],
                data: {
                    'changes': changes
                }
            });
        }
    }

    // FIXME - hack - must override default Binding
    _export($e) {
        const mode = $e.data('export');

        let data = {};

        if (mode === 'selection') {
            data['ids'] = [];
            this.$tableBody.find('tr.selected[data-id]').each((e, elt) => {
                data['ids'].push($(elt).data('id'));
            });

            // avoid export if no row was selected
            if (data['ids'].length === 0) {
                return;
            }
        } else {
            data = this.table.ajax.params();
        }

        // do ajax call and send extra params
        if ($e.data('xhr')) {

            AjaxUtils.get({
                url: $e.data('xhr'),
                data: data,
                xhr_id: $e.data('xhr-id') || null,
                confirm: $e.data('confirm') || false,
                spinner: $e.data('spinner') || false
            });

        } else {
            window.location.href = $e.attr('href') + '?' + $.param(data);
        }
    }

    // ----- Api ----- //

    displayError(error = Translator.trans('loading_data_error'), icon = null) {
        let html = '<tr>';
        html += '<td class="text-danger text-center" colspan="100%">';
        if (icon) {
            html += `<i class="${icon} me-1"></i>`;
        }
        html += error;
        html += '</td>';
        html += '</tr>';
        this.$tableBody.html(html);
    }

    displaySpinner() {
        this.$tableBody.html(this.$tableBody.data('spinner'));
    }

    reload(paging = true) {
        this.table.draw(paging);
    }

}
