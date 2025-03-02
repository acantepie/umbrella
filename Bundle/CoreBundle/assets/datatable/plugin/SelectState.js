export const PAGE_NOT_SELECTED = 0
export const PAGE_SELECTED = 1
export const PAGE_INDETERMINATE = -1

export default class SelectState {
    constructor() {
        this.ids = new Set()
        this.pageIds = new Map()
        this._pageState = null
    }

    clearSelection() {
        this.ids = new Set()
        this._pageState = PAGE_NOT_SELECTED
    }

    clearPageIds() {
        this.pageIds = new Map()
        this._pageState = null
    }

    addPageId(id, data = {}) {
        this.pageIds.set(id, data)
        this._pageState = null
    }

    addSelectedId(id) {
        this.ids.add(id)
        this._pageState = null
    }

    removeSelectedId(id) {
        this.ids.delete(id)
        this._pageState = null
    }

    isIdSelected(id) {
        return this.ids.has(id)
    }

    setPageState(pageState) {
        this._pageState = pageState
    }

    getPageState() {
        if (null !== this._pageState) {
            return this._pageState
        }

        let c = 0
        this.pageIds.forEach((_, id) => {
            if (this.isIdSelected(id)) {
                c++
            }
        })

        if (c === 0) {
            this._pageState = PAGE_NOT_SELECTED
        } else if (c === this.pageIds.size) {
            this._pageState = PAGE_SELECTED
        } else {
            this._pageState = PAGE_INDETERMINATE
        }

        return this._pageState
    }

    count() {
        return this.ids.size
    }

}
