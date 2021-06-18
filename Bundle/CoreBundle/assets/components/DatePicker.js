import 'flatpickr';
import 'flatpickr/dist/l10n/fr.js'

export default class DatePicker extends HTMLInputElement {

    constructor() {
        super();
        this.options = JSON.parse(this.dataset.options);
        this.options['locale'] = umbrella.LANG;
    }

    connectedCallback() {
        this.flatpickr = flatpickr(this, this.options);
    }

    disconnectedCallback() {
        this.flatpickr.destroy();
    }
}

