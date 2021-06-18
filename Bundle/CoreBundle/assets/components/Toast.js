import Toastify from 'toastify-js'

class Toast {


    defaultOptions = {
        close: true,
        duration: 3000,
        className: 'umbrella-toast',
        escapeMarkup: false,
        gravity: 'top',
        position: 'right',
        stopOnFocus: true,
    }

    show(type, text, title = null, options = {}) {
        options = {...this.defaultOptions, ...options}

        options['className'] += ' umbrella-toast-' + type

        let html = '<div class="umbrella-toast-wrapper">'
        if (title) {
            html += '<div class="umbrella-toast-head">' + title + '</div>';
        }
        html += '<div class="umbrella-toast-body">' + text + '</div>'
        html += '</div>'

        options['text'] = html
        Toastify(options).showToast()
    }

    error(text, title = null, options = {}) {
        this.show('error', text, title, options)
    }

    warning(text, title = null, options = {}) {
        this.show('warning', text, title, options)
    }

    success(text, title = null, options = {}) {
        this.show('success', text, title, options)
    }

    info(text, title = null, options = {}) {
        this.show('info', text, title, options)
    }

}

export default new Toast();