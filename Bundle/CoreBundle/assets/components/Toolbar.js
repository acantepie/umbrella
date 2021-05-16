/**
 * Custom events:
 * tb:change
 */
export default class Toolbar extends HTMLElement {

    constructor() {
        super();

        this.form = this.querySelector('form')

        this.events = true
        this.timer = null;
    }

    connectedCallback() {
        const $form = $(this.form)

        $form.on('change', 'select', () => {
            this._triggerChange(100);
        });

        $form.on('change paste input', 'input[type=search], input[type=text]', () => {
            this._triggerChange(200);
        });
    }

    // Avoid spam change event
    _triggerChange(wait = 0) {
        clearTimeout(this.timer);

        this.timer = setTimeout(() => {
            if (this.events) {
                this.dispatchEvent(new Event('tb:change'));
            }
        }, wait);
    }

    enableEvents() {
        this.events = true
    }

    disableEvents() {
        this.events = false
    }

    setMode(mode) {
        this.setAttribute('data-mode', mode)
    }

    setAlert(html) {
        this.querySelector('.toolbar-alert').innerHTML = html
    }

    getData() {
        return $(this.form).serializeFormToJson();
    }

    reset() {
        this.disableEvents()
        this.form.reset()
        this.enableEvents()

        this._triggerChange()
    }
}