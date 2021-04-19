/**
 * Custom events:
 * tb:change
 */
export default class Toolbar extends HTMLElement {

    constructor() {
        super();
        this.$view = $(this);
        this.$form = this.$view.find('.js-toolbar-form');

        this.quiet = false;
        this.timer = null;
    }

    connectedCallback() {
        this.$view.on('change', 'select:not([data-toolbar-type]), input:not([data-toolbar-type])', () => {
            this._triggerChange(100); // avoid spam
        });

        this.$view.on('change paste input', '[data-toolbar-type=search]', () => {
            this._triggerChange(200); // avoid spam
        });
    }

    // Avoid spam change event
    _triggerChange(wait = 0) {
        clearTimeout(this.timer);

        if (this.quiet) {
            return;
        }

        this.timer = setTimeout(() => {
            this.dispatchEvent(new Event('tb:change'));
        }, wait);
    }

    // Api

    getData() {
        return this.$form.length
            ? this.$form.serializeFormToJson()
            : [];
    }

    reset() {
        if (this.$form.length) {

            this.quiet = true;
            this.$form[0].reset(); // doens't trigger any change
            this.quiet = false;

            this._triggerChange();
        }
    }
}