import 'jquery-treetable'

export default class TreePlugin {

    constructor(options = {}) {
        this.expanded = options.expanded ?? false
        this.umbrellaDatatable = null
    }

    /**
     * @param {UmbrellaDataTable} umbrellaDatatable
     */
    configure(umbrellaDatatable) {
        this.umbrellaDatatable = umbrellaDatatable
        umbrellaDatatable.datatable.on('draw', () => this._drawTree())
    }

    _drawTree() {
        // Jquery plugin - deal with it
        const $table = $(this.umbrellaDatatable.table)

        // Retreive expanded nodes list from old Tree
        const expandedNodesId = [];
        if ($table.data('treetable') && $table.data('treetable').nodes) {

            for (const node of $table.data('treetable').nodes) {
                if (node.row.hasClass('expanded')) {
                    expandedNodesId.push(node.id);
                }
            }
        }

        $table.treetable({
            stringExpand: '',
            stringCollapse: '',
            expandable: true,
            clickableNodeNames: false,
            expanderTemplate: '<a href><i class="mdi mdi-chevron-right"></i></a>',
            initialState: this.expanded ? 'expanded' : 'collapsed'
        }, true);

        // Restore previously extanded nodes
        for (let nodeId of expandedNodesId) {
            try {
                $table.treetable('expandNode', nodeId);
            } catch (error) {
                // continue regardless of error
            }
        }
    }
}
