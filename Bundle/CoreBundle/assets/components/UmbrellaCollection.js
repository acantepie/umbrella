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
        this.$view.on('click', '.js-add-row', (e) => {
            e.preventDefault();
            this.index += 1;
            const regexp = new RegExp(this._prototype_name, "g");
            const $newRow = $(this._prototype.replace(regexp, this.index));

            this.$view.data('index', this.index);
            this.$view.find('tbody').first().append($newRow);

            this.toggleAdd();
            this.$view.trigger('form:row:add', [$newRow]);
        });

        // bind delete row
        this.$view.on('click', '.js-del-row', (e) => {
            e.preventDefault();

            $(e.currentTarget).closest('tr').remove();
            this.toggleAdd();

            this.$view.trigger('form:row:del');
        });

        // before submit => refresh input row order
        this.$view.closest('form').on('submit', () => {
            let order = 0;
            this.$view.find('.js-order').each((i, e) => {
                $(e).val(order);
                order++;
            });
        });

        // sorting
        if (this.$view.data('sortable')) {
            dragula({
                containers: [this.$view.find('tbody')[0]],
                moves: function (el, source, handle, sibling) {
                    return handle.classList.contains('js-drag-handle') || handle.parentNode.classList.contains('js-drag-handle');
                },
                mirrorContainer: this.$view.find('tbody')[0]
            });
        }
    }

    count() {
        return this.$view.find('tbody tr').length;
    }

    toggleAdd() {
        if (this.maxLength > 0) {
            this.$view.find('.js-add-row').toggleClass('d-none', this.count() >= this.maxLength);
        }
    }
}
