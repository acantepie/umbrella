export default function configureHandler(handler) {
    handler.registerAction('show_toast', (params) => {
        umbrella.Toast.show(params['type'], params['text'], params['title'], params['options']);
    });

    handler.registerAction('show_modal', (params) => {
        let $modal = $(params.value);
        let $opened_modal = $('.js-umbrella-modal.show');

        if ($opened_modal.length) {
            $opened_modal.html($modal.find('.modal-dialog'));

        } else {
            $modal.on('hidden.bs.modal', (e) => {
                $(e.target).data('bs.modal', null);
                $(e.target).remove();
            });

            $modal.modal('show');
        }
    });

    handler.registerAction('close_modal', (params) => {
        let $opened_modal = $('.js-umbrella-modal.show');
        if ($opened_modal.length) {
            $opened_modal.modal('hide');
        }
    });

    handler.registerAction('eval', (params) => {
        eval(params.value);
    });

    handler.registerAction('redirect', (params) => {
        window.location = params.value;
    });

    handler.registerAction('reload', (params) => {
        window.location.href = window.location.href.split('#')[0];
    });

    handler.registerAction('update', (params) => {
        const $view = $(params.selector);
        $view.html(params.value);
    });

    handler.registerAction('remove', (params) => {
        $(params.selector).remove();
    });

    handler.registerAction('call_webcomponent', (params) => {
        for (let element of document.querySelectorAll(params.selector)) {
            if (typeof element[params.method] === 'undefined') {
                console.warn('Function ' + params.method + '() doesn\'t exist for custom element ', element)
            } else {
                element[params.method](...params.method_params)
            }
        }
    });

    handler.registerAction('download', (params) => {
        const link = document.createElement('a')

        link.href = URL.createObjectURL(new Blob([params.content]));

        if (params.filename) {
            link.download = params.filename
        }

        link.click()
    });

    handler.setErrorHandler((requestObject, error, errorThrown) => {
        if (requestObject.status === 401) {
            umbrella.Toast.warning('401 - ' + umbrella.Translator.trans('unauthorized_error'));

        } else if (requestObject.status === 403) {
            umbrella.Toast.warning('403 - ' + umbrella.Translator.trans('forbidden_error'));

        } else if (requestObject.status === 404) {
            umbrella.Toast.warning('404 - ' + umbrella.Translator.trans('notfound_error'));

        } else {
            umbrella.Toast.error(umbrella.Translator.trans('other_error'));
        }
    })

}
