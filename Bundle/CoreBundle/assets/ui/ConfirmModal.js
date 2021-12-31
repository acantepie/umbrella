import './ConfirmModal.scss'

class ConfirmModal {

    static template = '<div class="modal confirm-modal fade" tabindex="-1" id="confirm-modal">' +
        '<div class="modal-dialog modal-dialog-centered" role="document">' +
        '<div class="modal-content">' +
        '<div class="modal-icon"><i class="uil-comment-exclamation"></i></div>' +
        '<div class="modal-body">__text__</div>' +
        '<div class="modal-footer">' +
        '<button type="button" class="btn btn-danger btn-cancel" data-bs-dismiss="modal"><i class="mdi mdi-close me-1"></i> __cancel__</button>' +
        '<button type="button" class="btn btn-success btn-confirm"><i class="mdi mdi-check me-1"></i> __confirm__</button></div></div></div></div>';

    constructor() {
        this.modal = null
    }

    show(options = {}) {

        // test if a confirm modal is shown
        if ('show' === document.body.getAttribute('data-confirm-modal')) {
            console.warn('Action prevented, a confirm modal is already opened.');
            return;
        }

        // Flag confirm modal as shown
        document.body.setAttribute('data-confirm-modal', 'show')


        const defaultOptions = {
            text: '',
            cancel_text: umbrella.Translator.trans('cancel'),
            confirm_text: umbrella.Translator.trans('confirm'),
            confirm: () => {}
        };

        options = {...defaultOptions, ...options};

        this.hide();

        let html = ConfirmModal.template.replace('__text__', options['text']);
        html = html.replace('__cancel__', options['cancel_text']);
        html = html.replace('__confirm__', options['confirm_text']);

        const template = document.createElement('div')
        template.innerHTML = html

        const modalElement = template.firstChild
        modalElement.addEventListener('hidden.bs.modal', (e) => {
            document.body.removeAttribute('data-confirm-modal')
            modalElement.remove()
        })
        modalElement.addEventListener('keypress', (e) => {
            if (e.which === 13) {
                options['confirm']();
                this.hide();
            }
        })
        modalElement.querySelector('.btn-confirm').addEventListener('click', (e) => {
            options['confirm']();
            this.hide();
        })
        document.body.appendChild(modalElement)

        this.modal = new bootstrap.Modal(modalElement)
        this.modal.show();
    }

    hide() {
        if (this.modal) {
            this.modal.hide();
        }
    }
}

export default new ConfirmModal()
