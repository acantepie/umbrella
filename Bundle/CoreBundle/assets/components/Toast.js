export default class Toast extends HTMLElement {
    constructor() {
        super();

        this.$view = $(this);
        this.options = this.$view.data('options');
    }

    connectedCallback() {
        toastr.options = this.options;
        toastr[this.options['type']](this.options['text'], this.options['title']);
    }
}
