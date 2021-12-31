import AjaxUtils from '../../utils/AjaxUtils';

export default class RowReorderPlugin {

    constructor(options = {}) {
        this.url = options.url
    }

    /**
     * @param {UmbrellaDataTable} umbrellaDatatable
     */
    configure(umbrellaDatatable) {
        umbrellaDatatable.datatable.on('row-reorder', this._rowReorder.bind(this))
    }

    _rowReorder(e, details, edit) {

        let changes = [];
        for (let i = 0, ien = details.length; i < ien; i++) {
            changes.push({
                id: details[i].node.dataset.id,
                new: details[i].newPosition,
                old: details[i].oldPosition
            });
        }

        if (changes.length > 0) {
            AjaxUtils.post({
                xhr_id: 'tb-order',
                url: this.url,
                data: {
                    'changes': changes
                }
            });
        }
    }
}
