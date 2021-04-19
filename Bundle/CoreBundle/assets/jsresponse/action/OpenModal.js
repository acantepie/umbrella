import JsResponseAction from "../JsResponseAction";

export default class OpenModal extends JsResponseAction {
    eval(params) {
        let $modal = $(params.value);
        let $opened_modal = $('.js-umbrella-modal.show');

        if ($opened_modal.length) {
            $opened_modal.html($modal.find('.modal-dialog'));
            umbrellaApp.mount($opened_modal);

        } else {
            // Remove this one day ...
            // HACK : bs 4 modal doesn't execute script
            $modal.on('shown.bs.modal', (e) => {
                const $scripts = $(e.target).find('script');
                $.each($scripts, (i, s) => {
                    eval($(s).html());
                });
            });
            $modal.on('hidden.bs.modal', (e) => {
                $(e.target).data('bs.modal', null);
                $(e.target).remove();
            });

            $modal.modal('show');
        }
    }
}