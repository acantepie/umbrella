import dragula from 'dragula';

export default class UmbrellaCollection extends HTMLElement {

    constructor() {
        super();

        this._prototype = this.dataset.prototype;
        this._prototype_name = this.dataset.prototypeName
        this.index = parseInt(this.dataset.index)
        this.maxLength = parseInt(this.dataset.maxLength)
        this.sortable = this.dataset.sortable === 'true'

        this.addAction = this.querySelector('.js-add-item')
        this.deleteActions = this.querySelectorAll('.js-del-item')
        this.itemsContainer = this.querySelector('.js-item-container')
    }

    connectedCallback() {
        this._updateAddAction();

        if (this.addAction) {
            this.addAction.addEventListener('click', e => {
                e.preventDefault();
                this.addRow();
            })
        }

        this.deleteActions.forEach(e => e.addEventListener('click', e => {
            e.preventDefault();
            this.deleteRow(e.target.closest('.js-item'));
        }))

        if (this.sortable) {
            dragula({
                containers: [this.itemsContainer],
                moves: function (el, source, handle, sibling) {
                    return handle.classList.contains('js-drag-handle') || handle.parentNode.classList.contains('js-drag-handle');
                },
                mirrorContainer: this.itemsContainer
            });
        }
    }

    deleteRow(row) {
        row.remove()
        this._updateAddAction()
    }

    addRow() {
        this.index += 1;
        const regexp = new RegExp(this._prototype_name, 'g');

        const template = document.createElement('tbody')
        template.innerHTML = this._prototype.replace(regexp, this.index.toString())

        const rowElement = template.firstChild
        const delAction = rowElement.querySelector('.js-del-item')

        if (delAction) {
            delAction.addEventListener('click', (e) => {
                e.preventDefault()
                this.deleteRow(rowElement)
            })
        }

        this.dataset.index = this.index.toString()
        this.itemsContainer.appendChild(rowElement)
        this._updateAddAction()
    }

    count() {
        return this.itemsContainer.querySelectorAll('.js-item').length
    }

    _updateAddAction() {
        if (this.maxLength > 0) {
            this.addAction.classList.toggle('d-none', this.count() >= this.maxLength)
        }
    }
}
