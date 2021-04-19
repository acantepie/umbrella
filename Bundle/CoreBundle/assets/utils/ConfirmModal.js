import "./ConfirmModal.scss"

export default class ConfirmModal {

    static template = '<div class="modal confirm-modal fade" tabindex="-1" id="confirm-modal">' +
        '<div class="modal-dialog modal-dialog-centered" role="document">' +
        '<div class="modal-content">' +
        '<div class="modal-body">__text__</div>' +
        '<div class="modal-footer">' +
        '<button type="button" class="btn btn-outline-light btn-cancel" data-dismiss="modal">__cancel__</button>' +
        '<button type="button" class="btn btn-outline-light btn-confirm">__confirm__</button></div></div></div></div>';

    static $modal = null;

    static show(options = {}) {

        const defaultOptions = {
            text: '',
            cancel_text: Translator.trans('cancel'),
            confirm_text: Translator.trans('confirm'),
            confirm: () => {}
        };

        options = {...defaultOptions, ...options};

        ConfirmModal.hide();

        let html = ConfirmModal.template.replace('__text__', options['text']);
        html = html.replace('__cancel__', options['cancel_text']);
        html = html.replace('__confirm__', options['confirm_text']);

        ConfirmModal.$modal = $(html);

        ConfirmModal.$modal.on('keypress', (e) => {
            if (e.which === 13) {
                options['confirm']();
                ConfirmModal.hide();
            }
        });
        ConfirmModal.$modal.on('click', '.btn-confirm', (e) => {
            options['confirm']();
            ConfirmModal.hide();
        });

        ConfirmModal.$modal.on('hidden.bs.modal', () => ConfirmModal.remove());

        ConfirmModal.$modal.modal('show');
    }

    static hide() {
        if (ConfirmModal.$modal) {
            ConfirmModal.$modal.modal('hide');
        }
    }

    static remove() {
        $('#confirm-modal').remove();
    }
}
