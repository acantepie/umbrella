export default class RowDetailsPlugin {

    constructor() {
        this.umbrellaDatatable = null
    }

    /**
     * @param {UmbrellaDataTable} umbrellaDatatable
     */
    configure(umbrellaDatatable) {
        this.umbrellaDatatable = umbrellaDatatable

        this.umbrellaDatatable.datatable.on('draw', () => {
            this.umbrellaDatatable.tbody.querySelectorAll('.js-toggle-child-row-btn').forEach($btn => this._bind($btn))
        })

    }

    _bind($btn) {
        $btn.addEventListener('click', evt => {
            evt.preventDefault()
            const $row = $btn.closest('tr')
            const row = this.umbrellaDatatable.datatable.row($row)

            const html = $btn.firstElementChild.innerHTML

            if (row) {
                if (row.child.isShown()) {
                    row.child.hide()
                    $btn.classList.add('collapsed');
                } else {
                    row.child(html)
                    row.child.show()
                    $btn.classList.remove('collapsed');
                }
            }
        })
    }


}
