import './Spinner.scss'

class Spinner {

    constructor() {
        this.body = document.querySelector('body');
        this.initialOverflowY = this.body.style.overflowY;
    }

    show() {
        const spinnerEl = document.createElement('div')
        spinnerEl.id = 'spinner'
        spinnerEl.innerHTML = `
            <div id="status">
                <div class="bouncing-loader">
                    <div class="bg-primary"></div>
                    <div class="bg-danger"></div>
                    <div class="bg-success"></div>
                </div>
            </div>`

        this.hide();
        this.body.style.overflowY = 'hidden'
        this.body.appendChild(spinnerEl);
    }

    hide() {
        const spinnerEl = document.querySelector('#spinner');
        if (spinnerEl) {
            this.body.style.overflowY = this.initialOverflowY
            spinnerEl.remove()
        }
    }
}

export default new Spinner()