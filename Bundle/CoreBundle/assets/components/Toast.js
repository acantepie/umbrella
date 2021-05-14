class Toast {

    top = '80px'
    right = '10px'
    left = null
    bottom = null
    zIndex = 9999

    options = {
        autohide: true,
        delay: 3000
    }

    constructor() {
        this.containerEl = null;
    }

    _initContainer() {
        if (null === this.containerEl) {

            if (null === document.querySelector('.toast-container')) {
                this.containerEl = document.createElement('div')
                this.containerEl.className = 'toast-container'
                document.querySelector('body').appendChild(this.containerEl)
            } else {
                this.containerEl = document.querySelector('.toast-container')
            }

        }

        this.containerEl.style.top = this.top
        this.containerEl.style.right = this.right
        this.containerEl.style.left = this.left
        this.containerEl.style.bottom = this.bottom
        this.containerEl.style.zIndex = this.zIndex
        this.containerEl.style.position = 'fixed'
    }

    // render and show a toast from an HTML string
    renderFromHTML(html) {
        this._initContainer()

        const templateEl = document.createElement('template')
        templateEl.innerHTML = html.trim()

        const toastEl = templateEl.content.firstChild
        this.containerEl.appendChild(toastEl)

        this.show(toastEl)
    }

    // show a toast from node
    show(el) {
        this._initContainer()

        const bsToast = new bootstrap.Toast(el, this.options)
        bsToast.show()
        el.addEventListener('hidden.bs.toast', () => {
            el.remove()
        })
    }

    alert(type, text, title = null) {
        let toastClass = null
        let btnCloseClass = null

        switch (type) {
            case 'error':
                toastClass = 'bg-danger text-white border-0'
                btnCloseClass = 'btn-close-white'
                break

            case 'warning':
                toastClass = 'bg-warning text-white border-0'
                btnCloseClass = 'btn-close-white'
                break

            case 'success':
                toastClass = 'bg-success text-white border-0'
                btnCloseClass = 'btn-close-white'
                break

            case 'info':
                toastClass = 'bg-info text-white border-0'
                btnCloseClass = 'btn-close-white'
                break
        }

        let html = `<div class="toast show d-flex align-items-center ${toastClass}" role="alert" aria-live="assertive" aria-atomic="true">`
        html += '<div class="toast-body">'
        if (title) {
            html += `<div class="fw-bold">${title}</div>`
        }
        html += `<div>${text}</div>`
        html += '</div>'
        html += `<button type="button" class="btn-close ms-auto me-2 ${btnCloseClass}" data-bs-dismiss="toast" aria-label="Close"></button>`
        html += '</div>'

        this.renderFromHTML(html)

    }

    alertError(text, title = null) {
        this.alert('error', text, title)
    }

    alertWarning(text, title = null) {
        this.alert('warning', text, title)
    }

    alertSuccess(text, title = null) {
        this.alert('success', text, title)
    }

    alertInfo(text, title = null) {
        this.alert('info', text, title)
    }

}

export default new Toast();