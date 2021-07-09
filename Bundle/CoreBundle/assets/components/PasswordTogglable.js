export default class PasswordTogglable extends HTMLDivElement {

    constructor() {
        super()
        this.showPassword = false
        this.classList.add('input-group')

        this.onClick = this.onClick.bind(this)
    }

    connectedCallback() {
        const e = document.createElement('div')
        e.className = 'input-group-text'
        e.innerHTML = `${this.icon()}`

        this.appendChild(e)
        this.querySelector('.input-group-text').addEventListener('click', this.onClick)
    }

    onClick(e) {
        e.preventDefault()
        this.showPassword = !this.showPassword
        this.querySelector('.input-group-text').innerHTML = this.icon()
        this.querySelector('input').type = this.showPassword ? 'text' : 'password'
    }

    icon() {
        return this.showPassword
            ? '<i class="mdi mdi-eye-off">'
            : '<i class="mdi mdi-eye"></i>'
    }
}
