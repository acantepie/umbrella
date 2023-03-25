export default class RowDetailsPlugin {

    constructor() {
        this.umbrellaDatatable = null
    }

    /**
     * @param {UmbrellaDataTable} umbrellaDatatable
     */
    configure(umbrellaDatatable) {
        this.umbrellaDatatable = umbrellaDatatable

        umbrellaDatatable.datatable.on('draw', () => {

            umbrellaDatatable.tbody.querySelectorAll('.details-handle').forEach(action => {
                action.addEventListener('click', (e) => {
                    e.preventDefault()
                    this.__toggleDetails(action)
                })

                if ('true' === action.ariaExpanded) {
                    this.__toggleDetails(action)
                }
            })
        })
    }

    __toggleDetails(action) {
        const template = action.querySelector('template')

        if (null === template) {
            return;
        }

        const row = action.closest('.dt-row')
        const dataTableRow = this.umbrellaDatatable.datatable.row(row)

        if (dataTableRow.child.isShown()) {
            action.ariaExpanded = false
            dataTableRow.child.hide()
        } else {
            action.ariaExpanded = true
            dataTableRow.child(template.innerHTML).show()
        }
    }
}
