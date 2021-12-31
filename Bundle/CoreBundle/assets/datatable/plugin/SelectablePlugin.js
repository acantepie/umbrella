export default class SelectablePlugin {

    constructor(options = {}) {
        this.multiple = options.multiple ?? true

        this.pageSelector = null
        this.pageState = null

        this.selectedIds = new Set()
        this.selectableRows = new Map()

        this.umbrellaDatatable = null
    }

    /**
     * @param {UmbrellaDataTable} umbrellaDatatable
     */
    configure(umbrellaDatatable) {
        this.umbrellaDatatable = umbrellaDatatable

        // page selector
        const pageSelectorHandler = umbrellaDatatable.thead.querySelector('.select-handle')
        if (pageSelectorHandler) {
            pageSelectorHandler.addEventListener('click', (e) => this.toggleSelectPage())
            this.pageSelector = pageSelectorHandler.querySelector('input')
        }

        umbrellaDatatable.datatable.on('draw', () => {

            // refresh selectable row + bind row event
            this.selectableRows = new Map()

            umbrellaDatatable.tbody.querySelectorAll('tr.row-selectable').forEach(row => {
                const id = row.dataset.id

                // add on selectable element
                this.selectableRows.set(id, row)

                // bind handler
                row.querySelector('.select-handle').addEventListener('click', () => this.toggleSelectRow(id))

                // restore selection
                if (this.selectedIds.has(id)) {
                    this.__updateRowState(row, true)
                }

            })

            // refresh state
            this.__updateState()

        })

        // register api
        this.umbrellaDatatable.selectRow = this.selectRow.bind(this)
        this.umbrellaDatatable.unselectRow = this.unselectRow.bind(this)
        this.umbrellaDatatable.selectPage = this.selectPage.bind(this)
        this.umbrellaDatatable.unselectPage = this.unselectPage.bind(this)
        this.umbrellaDatatable.unselectAll = this.unselectAll.bind(this)
        this.umbrellaDatatable.getSelectedIds = this.getSelectedIds.bind(this)
    }

    __updateState() {
        if (this.multiple) {
            let countSelectedOnPage = 0;

            for (let id of this.selectedIds) {
                if (this.selectableRows.has(id)) {
                    countSelectedOnPage++
                }
            }

            if (countSelectedOnPage === 0) {
                this.pageState = 'none'

                if (this.pageSelector) {
                    this.pageSelector.checked = false
                    this.pageSelector.indeterminate = false
                }

            } else if (countSelectedOnPage === this.selectableRows.size) {
                this.pageState = 'all'

                if (this.pageSelector) {
                    this.pageSelector.checked = true
                    this.pageSelector.indeterminate = false
                }

            } else {
                this.pageState = 'many'

                if (this.pageSelector) {
                    this.pageSelector.checked = false
                    this.pageSelector.indeterminate = true
                }
            }
        }

        this.__updateContent()
    }

    __updateContent() {
        const content = this.umbrellaDatatable.querySelector('.select-content')
        if (null === content) {
            return
        }

        if (0 === this.selectedIds.size) {
            content.hidden = true
            return
        }

        content.hidden = false
        content.innerHTML = '<div class="p-2 bg-light">' + umbrella.Translator.trans('row_selected', {'%c%': this.selectedIds.size}) + '</div>'

        const handler = content.querySelector('.unselectall-handler')
        if (handler) {
            handler.addEventListener('click', (e) => {
                e.preventDefault()
                this.unselectAll()
            })
        }
    }

    __updateRowState(row, state) {
        const input = row.querySelector('.select-handle input')

        if (state) {
            row.classList.add('selected')
            input.checked = true
        } else {
            row.classList.remove('selected')
            input.checked = false
        }
    }

    // --- Api --- //

    toggleSelectPage() {
        if (this.multiple) {
            if (this.pageState === 'all') {
                this.unselectPage()
            } else {
                this.selectPage()
            }
        }
    }

    toggleSelectRow(id) {
        if (this.selectedIds.has(id)) {
            this.unselectRow(id)
        } else {
            this.selectRow(id)
        }
    }

    selectRow(id) {
        if (!this.multiple) {
            this.unselectAll(false)
        }

        this.selectedIds.add(id)
        const row = this.selectableRows.get(id)

        if (row) {
            this.__updateRowState(row, true)
        }

        this.__updateState()
    }

    unselectRow(id) {
        this.selectedIds.delete(id)
        const row = this.selectableRows.get(id)

        if (row) {
            this.__updateRowState(row, false)
        }

        this.__updateState()
    }

    selectPage() {
        if (this.multiple) {
            this.selectableRows.forEach((row, id) => {
                this.selectedIds.add(id)
                this.__updateRowState(row, true)
            })

            this.__updateState()
        }
    }

    unselectPage() {
        if (this.multiple) {
            this.selectableRows.forEach((row, id) => {
                this.selectedIds.delete(id)
                this.__updateRowState(row, false)
            })

            this.__updateState()
        }
    }

    unselectAll(updateState = true) {
        this.selectableRows.forEach((row, id) => {
            this.__updateRowState(row, false)
        })
        this.selectedIds.clear()

        if (updateState) {
            this.__updateState()
        }
    }

    getSelectedIds() {
        return Array.from(this.selectedIds)
    }

}
