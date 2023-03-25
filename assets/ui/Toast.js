import Toastify from 'toastify-js'

class Toast {


    defaultOptions = {
        close: true,
        duration: 3000, // -1 for debug
        className: 'umbrella-toast',
        escapeMarkup: false,
        gravity: 'top',
        position: 'right',
        stopOnFocus: true,
    }

    show(type, text, title = null, options = {}) {
        options = {...this.defaultOptions, ...options}

        // toast can be closed ?
        const close = options['close']

        options['className'] += ' umbrella-toast-' + type

        let html = '<div class="umbrella-toast-wrapper">'
        if (title) {
            html += '<div class="umbrella-toast-head">' + title + '</div>';
        }
        html += '<div class="umbrella-toast-body">' + text + '</div>'
        html += '</div>'

        // use custom template for close btn
        if (close) {
            html += '<div class="umbrella-toast-close"><i class="mdi mdi-close"></i></div>'
        }

        options['close'] = false // don't use ugly library close btn
        options['text'] = html
        const t = Toastify(options).showToast()

        // add event listener for close btn
        if (close) {
            t.toastElement.querySelector('.umbrella-toast-close').addEventListener('click', () => t.hideToast())
        }
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