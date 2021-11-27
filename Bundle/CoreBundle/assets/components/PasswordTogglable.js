export default class PasswordTogglable extends HTMLDivElement {

    constructor() {
        super()
        this.showPassword = false
        this.classList.add('input-icon')

        this.onClick = this.onClick.bind(this)
    }

    connectedCallback() {
        const e = document.createElement('a')
        e.href = '#'
        e.className = 'input-icon-addon'
        e.innerHTML = `${this.icon()}`

        this.appendChild(e)
        this.querySelector('.input-icon-addon').addEventListener('click', this.onClick)
    }

    onClick(e) {
        e.preventDefault()
        this.showPassword = !this.showPassword
        this.querySelector('.input-icon-addon').innerHTML = this.icon()
        this.querySelector('input').type = this.showPassword ? 'text' : 'password'
    }

    icon() {
        return this.showPassword
            ? '<i class="mdi mdi-eye-off">'
            : '<i class="mdi mdi-eye"></i>'
    }
}
