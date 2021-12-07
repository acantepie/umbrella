export default class FullScreenToggler extends HTMLAnchorElement {

    constructor() {
        super()
        this.element = document.querySelector('html')
    }

    connectedCallback() {
        this.addEventListener('click', this.toggle.bind(this))
    }

    toggle() {
        if (document.fullscreenEnabled) {
            if (document.fullscreenElement) {
                document.exitFullscreen()
            } else {
                this.element.requestFullscreen()
            }
        }
    }
}
