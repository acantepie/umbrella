import AjaxUtils from "../utils/AjaxUtils";
import BindUtils from "../utils/BindUtils"

import i18n from "./DataTable.i18n.js";

export default class DataTable extends HTMLElement {

    constructor() {
        super();
        this.$view = $(this);

        this.$table = this.$view.find('table.datatable');
        this.$tableBody = this.$table.find('tbody');
        this.$selectionInfo = null

        this.options = this.$view.data('options');
        this.table = null;
        this.toolbar = this.querySelector('umbrella-toolbar');
        this.timer = null;

        this.selectedIds = new Set([]);

        this.reload = this.reload.bind(this)
        this._handleData = this._handleData.bind(this)
        this._handleError = this._handleError.bind(this)
        this._preDrawCallback = this._preDrawCallback.bind(this)
        this._drawCallback = this._drawCallback.bind(this)
        this._rowReorder = this._rowReorder.bind(this)

        this._buildOptions();
    }

    connectedCallback() {
        this.table = this.$table.DataTable(this.options);
        this._startAutoReload(this.options['poll_interval']);

        if (this.toolbar) {
            this.toolbar.addEventListener('tb:change', this.reload)
        }

        // row re-order
        if (this.options['rowReorder']) {
            this.table.on('row-reorder', this._rowReorder);
        }

        // row select
        this.$tableBody.on('click', '.row-selector', (e) => {
            this.toggleRowSelection($(e.currentTarget).closest('tr[data-id]'));
        })

        // custom event
        this.$view.on('click', '[data-onclick]', (e) => {
            const $e = $(e.currentTarget)
            e.preventDefault();

            switch ($e.data('onclick')) {
                case 'reset':
                    if (this.toolbar) {
                        this.toolbar.reset();
                    }
                    return

                case 'select-page':
                    this.selectPage()
                    return

                case 'unselect-page':
                    this.unselectPage()
                    return

                case 'unselect-all':
                    this.unselectAll()
                    return

                case 'show-details':
                    this.toggleRowDetails($e)
                    return

                case 'send-selection':
                case 'send-filter':
                    this._sendExtraData($e)
                    return

            }

        })


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
        if (umbrella.LANG in i18n) {
            this.options['language'] = i18n[umbrella.LANG];
        }

        this.options['ajax']['data'] = this._handleData;
        this.options['ajax']['error'] = this._handleError;
        this.options['preDrawCallback'] = this._preDrawCallback;
        this.options['drawCallback'] = this._drawCallback;
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
            this.displayError(umbrella.Translator.trans('disconnected_error'));
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

        this._drawTree();
        this._drawSelection();
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

    _drawSelection() {
        if (0 === this.selectedIds.size) {
            return
        }

        this.$tableBody.find('tr[data-id]').each((i, e) => {
            const $row = $(e);
            if (this.selectedIds.has($row.data('id'))) {
                this.selectRow($row)
            }
        })
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

    _sendExtraData($e) { // FIXME - hack - must override default Binding
        const mode = $e.data('onclick');

        let data = {};

        if (mode === 'send-selection') {

            if (0 === this.selectedIds.size) {
                return
            }

            data['ids'] = [...this.selectedIds];

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

    displayError(error = umbrella.Translator.trans('loading_data_error'), icon = null) {
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

    selectPage(updateInfo = true) {
        this.$tableBody.find('tr[data-id]').each((i, e) => {
            this.selectRow($(e), false)
        })

        if (updateInfo) {
            this.renderSelectionInfo()
        }
    }

    unselectPage(updateInfo = true) {
        this.$tableBody.find('tr[data-id]').each((i, e) => {
            this.unselectRow($(e), false)
        })

        if (updateInfo) {
            this.renderSelectionInfo()
        }
    }

    unselectAll(updateInfo = true) {
        this.unselectPage(false)
        this.selectedIds.clear()

        if (updateInfo) {
            this.renderSelectionInfo()
        }
    }

    toggleRowSelection($row, updateInfo = true) {
        this.selectedIds.has($row.data('id'))
            ? this.unselectRow($row, updateInfo)
            : this.selectRow($row, updateInfo)
    }

    selectRow($row, updateInfo = true) {
        this.selectedIds.add($row.data('id'))
        $row.addClass('selected')
        $row.find('.row-selector input[type=checkbox]').prop('checked', true)

        if (updateInfo) {
            this.renderSelectionInfo()
        }
    }

    unselectRow($row, updateInfo = true) {
        this.selectedIds.delete($row.data('id'))
        $row.removeClass('selected')
        $row.find('.row-selector input[type=checkbox]').prop('checked', false)

        if (updateInfo) {
            this.renderSelectionInfo()
        }
    }

    unselectRowId(id) {
        const $row = this.$tableBody.find('tr[data-id=' + id + ']')

        if ($row.length) {
            this.unselectRow($row, true)
        } else {
            this.selectedIds.delete(id)
            this.renderSelectionInfo()
        }
    }

    selectRowId(id) {
        const $row = this.$tableBody.find('tr[data-id=' + id + ']')

        if ($row.length) {
            this.selectRow($row, true)
        } else {
            this.selectedIds.add(id)
            this.renderSelectionInfo()
        }
    }

    renderSelectionInfo() {
        if (0 === this.selectedIds.size) {
            if (null !== this.$selectionInfo) {
                this.$selectionInfo.remove();
                this.$selectionInfo = null
            }
            return
        }

        if (null === this.$selectionInfo) {
            this.$selectionInfo = $('<div class="alert bg-light fade show"></div>');
            this.$table.before(this.$selectionInfo);
        }

        this.$selectionInfo.html(`<div>
            ${umbrella.Translator.trans('row_selected', {'%c%': this.selectedIds.size})}
            </div>`);
    }

    toggleRowDetails($e) {
        const details = $e.attr('row-details');

        if (!details) {
            return;
        }

        const $row = $e.closest('tr[data-id]');
        const row = this.table.row($row);

        if (row.child.isShown()) {
            row.child.hide();
            $row.removeClass('shown');
        } else {
            row.child(details).show();
            $row.addClass('shown');
        }
    }

}
