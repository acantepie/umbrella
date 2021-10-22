import dragula from 'dragula';

export default class UmbrellaCollection extends HTMLElement {

    constructor() {
        super();

        this.$view = $(this);
        this._prototype = this.$view.data('prototype');
        this._prototype_name = this.$view.data('prototype-name');
        this.index = this.$view.data('index');
        this.maxLength = this.$view.data('maxLength');
    }

    connectedCallback() {
        this.toggleAdd();

        // bind add row
        this.$view.on('click', '.js-add-item', (e) => {
            e.preventDefault();
            this.addRow();
        });

        // bind delete row
        this.$view.on('click', '.js-del-item', (e) => {
            e.preventDefault();

            $(e.currentTarget).closest('.js-item').remove();
            this.toggleAdd();

            this.$view.trigger('form:row:del');
        });

        // sorting
        if (this.$view.data('sortable')) {
            const container = this.querySelector('.js-item-container');

            dragula({
                containers: [container],
                moves: function (el, source, handle, sibling) {
                    return handle.classList.contains('js-drag-handle') || handle.parentNode.classList.contains('js-drag-handle');
                },
                mirrorContainer: container
            });
        }
    }

    addRow() {
        this.index += 1;
        const regexp = new RegExp(this._prototype_name, 'g');
        const $newRow = $(this._prototype.replace(regexp, this.index));

        this.$view.data('index', this.index);
        this.$view.find('.js-item-container').first().append($newRow);

        this.toggleAdd();
        this.$view.trigger('form:row:add', [$newRow]);
    }

    count() {
        return this.$view.find('.js-item-container .js-item').length;
    }

    toggleAdd() {
        if (this.maxLength > 0) {
            this.$view.find('.js-add-item').toggleClass('d-none', this.count() >= this.maxLength);
        }
    }
}
