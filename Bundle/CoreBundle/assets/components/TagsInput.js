export default class TagsInput extends HTMLSelectElement {

    constructor() {
        super();

        this.$view = $(this);
        this._options = this.$view.data('options');
        this._options['multiple'] = true;
        this._options['tags'] = true;
    }

    connectedCallback() {
        this.$view.select2(this._options);
    }

    disconnectedCallback() {
        this.$view.select2('destroy');
    }
}
