import Utils from 'umbrella_core/utils/Utils';

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
        this.form.querySelectorAll('select, input[type=checkbox], input[type=radio]').forEach(e => {
            e.addEventListener('change', () => this._triggerChange(100))
        })

        this.form.querySelectorAll('input[type=search], input[type=text]').forEach(e => {
            e.addEventListener('change', () => this._triggerChange(200))
            e.addEventListener('paste', () => this._triggerChange(200))
            e.addEventListener('input', () => this._triggerChange(200))
        })
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
        this.dataset.mode = mode
    }

    getMode() {
        return this.dataset.mode || 'default'
    }

    setAlert(html) {
        const alert = this.querySelector('.toolbar-alert')
        if (alert) {
            alert.innerHTML = html
        }
    }

    getData() {
        return Utils.objectify_formdata(new FormData(this.form))
    }

    reset() {
        this.disableEvents()
        this.form.reset()
        this.enableEvents()

        this._triggerChange()
    }
}