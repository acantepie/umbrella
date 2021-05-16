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

        this.selectedIds = new Set([]); // selection mode if selectedIds > 0

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

        this.toolbar.addEventListener('tb:change', this.reload)

        // row re-order
        if (this.options['rowReorder']) {
            this.table.on('row-reorder', this._rowReorder);
        }

        // row select
        this.$tableBody.on('click', '.row-selector', (e) => {
            this.toggleRowSelection($(e.currentTarget).closest('tr[data-id]'));
        })

        // handle tagged actions
        this.$view.on('click', '[data-tag^=dt]', (e) => {
            this._handleClickAction(e);
        })

        // handle
    }

    disconnectedCallback() {
        this.table.destroy();
        this._stopAutoReload();
    }

    _buildOptions() {
        if (umbrella.LANG in i18n) {
            this.options['language'] = i18n[umbrella.LANG];
        }

        this.options['processing'] = true
        this.options['ajax']['data'] = this._handleData;
        this.options['ajax']['error'] = this._handleError;
        this.options['preDrawCallback'] = this._preDrawCallback;
        this.options['drawCallback'] = this._drawCallback;
    }

    _handleData(data) {
        // avoid send useless data
        delete data['columns'];
        delete data['search'];

        // add dataTable id
        data['_dtid'] = this.id

        // add toolbar data
        data = {...data, ...this.toolbar.getData()}

        return data;
    }

    _handleError(requestObject, error, errorThrown) {
        if (requestObject.status === 401) {
            this.displayError(umbrella.Translator.trans('disconnected_error'));
        } else {
            if (requestObject.responseJSON && requestObject.responseJSON.error) {
                this.displayError(requestObject.responseJSON.error);
            } else {
                this.displayError(umbrella.Translator.trans('loading_data_error'));
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
        if (this.isSelectionMode()) {
            this.$tableBody.find('tr[data-id]').each((i, e) => {
                const $row = $(e);
                if (this.selectedIds.has($row.data('id'))) {
                    this.selectRow($row)
                }
            })
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

    // ----- Row reorder ----- //

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

    // ----- Custom actions ----- //

    _handleClickAction(e) {
        const $action = $(e.currentTarget)
        const tag = $action.attr('data-tag')

        switch (tag) {
            case 'dt:action':
                // no click to handle - let default behaviour
                break

            case 'dt:unselectall':
                e.preventDefault()
                this.unselectAll()
                break

            case 'dt:selectpage':
                e.preventDefault()
                this.selectPage()
                break

            case 'dt:unselectpage':
                e.preventDefault()
                this.unselectPage()
                break

            case 'dt:reset':
                e.preventDefault()
                this.toolbar.reset()
                break

            case 'dt:details':
                e.preventDefault()
                this.toggleRowDetails($action)
                break

            case 'dt:senddata':
                e.preventDefault()
                e.stopPropagation()
                this._doXhr($action);
                break

            default:
                console.warn('Unknow tag ', tag, $action[0])
                break
        }
    }

    _doXhr($e) {
        if (null === $e.data('xhr')) {
            console.warn('Can\'t handle action, missing data-xhr attribute', $e[0])
            return
        }

        let data = {}

        if (this.isSelectionMode()) {
            data['ids'] = [...this.selectedIds]
            data['mode'] = 'selection'
        } else {
            data = this.table.ajax.params()
            data['mode'] = 'default';
        }

        AjaxUtils.get({
            url: $e.data('xhr'),
            data: data,
            xhr_id: $e.data('xhr-id') || null,
            confirm: $e.data('confirm') || false,
            spinner: $e.data('spinner') || false
        });
    }


    _renderMode() {
        if (this.isSelectionMode()) {
            this.toolbar.setAlert(umbrella.Translator.trans('row_selected', {'%c%': this.selectedIds.size}));
            this.toolbar.setMode('selection')
        } else {
            this.toolbar.setAlert('')
            this.toolbar.setMode('default')
        }
    }

    // ----- Api ----- //

    displayError(error) {
        this.$tableBody.html(`
            <tr>
                <td class="text-danger text-center" colspan="100%">${error}</td>
            </tr>`);
    }

    reload(paging = true) {
        this.table.draw(paging);
    }

    isSelectionMode() {
        return this.selectedIds.size > 0
    }

    selectPage(renderMode = false) {
        this.$tableBody.find('tr[data-id]').each((i, e) => {
            this.selectRow($(e), false)
        })

        if (renderMode) this._renderMode()
    }

    unselectPage(renderMode = true) {
        this.$tableBody.find('tr[data-id]').each((i, e) => {
            this.unselectRow($(e), false)
        })

        if (renderMode) this._renderMode()
    }

    unselectAll(renderMode = true) {
        this.unselectPage(false)
        this.selectedIds.clear()

        if (renderMode) this._renderMode()
    }

    toggleRowSelection($row, renderMode = true) {
        this.selectedIds.has($row.data('id'))
            ? this.unselectRow($row, renderMode)
            : this.selectRow($row, renderMode)
    }

    selectRow($row, renderMode = true) {
        this.selectedIds.add($row.data('id'))
        $row.addClass('selected')
        $row.find('.row-selector input[type=checkbox]').prop('checked', true)

        if (renderMode) this._renderMode()
    }

    unselectRow($row, renderMode = true) {
        this.selectedIds.delete($row.data('id'))
        $row.removeClass('selected')
        $row.find('.row-selector input[type=checkbox]').prop('checked', false)

        if (renderMode) this._renderMode()
    }

    unselectRowId(id) {
        const $row = this.$tableBody.find('tr[data-id=' + id + ']')

        if ($row.length) {
            this.unselectRow($row, true)
        } else {
            this.selectedIds.delete(id)
            this._renderMode()
        }
    }

    selectRowId(id) {
        const $row = this.$tableBody.find('tr[data-id=' + id + ']')

        if ($row.length) {
            this.selectRow($row, true)
        } else {
            this.selectedIds.add(id)
            this._renderMode()
        }
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
