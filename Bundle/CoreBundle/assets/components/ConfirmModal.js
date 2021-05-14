import "./ConfirmModal.scss"

class ConfirmModal {

    static template = '<div class="modal confirm-modal fade" tabindex="-1" id="confirm-modal">' +
        '<div class="modal-dialog modal-dialog-centered" role="document">' +
        '<div class="modal-content">' +
        '<div class="modal-body">__text__</div>' +
        '<div class="modal-footer">' +
        '<button type="button" class="btn btn-outline-light btn-cancel" data-bs-dismiss="modal">__cancel__</button>' +
        '<button type="button" class="btn btn-outline-light btn-confirm">__confirm__</button></div></div></div></div>';

    constructor() {
        this.$modal = null
    }

    show(options = {}) {

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

        this.$modal = $(html);

        this.$modal.on('keypress', (e) => {
            if (e.which === 13) {
                options['confirm']();
                this.hide();
            }
        });
        this.$modal.on('click', '.btn-confirm', (e) => {
            options['confirm']();
            this.hide();
        });

        this.$modal.on('hidden.bs.modal', this.remove);

        this.$modal.modal('show');
    }

    hide() {
        if (this.$modal) {
            this.$modal.modal('hide');
        }
    }

    remove() {
        $('#confirm-modal').remove();
    }
}

export default new ConfirmModal()
