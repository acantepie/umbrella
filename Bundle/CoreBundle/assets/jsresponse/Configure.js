export default function configureHandler(handler) {
    handler.registerAction('show_toast', (params) => {
        umbrella.Toast.show(params['type'], params['text'], params['title'], params['options']);
    });

    handler.registerAction('show_modal', (params) => {

        // create modal template
        const template = document.createElement('div')
        template.innerHTML = params.value.trim()

        let modalElement = document.getElementById('umbrella-modal')

        if (modalElement) { // if modal exist => updated if from template
            modalElement.innerHTML = template.firstChild.innerHTML

        } else { // if modal not exist => create it
            modalElement = template.firstChild
            modalElement.addEventListener('hidden.bs.modal', modalElement.remove)
            document.body.appendChild(modalElement)
            const modal = new bootstrap.Modal(modalElement)
            modal.show()
        }
    });

    handler.registerAction('close_modal', (params) => {
        const modalElement = document.getElementById('umbrella-modal')
        if (modalElement) {
            const modal = bootstrap.Modal.getInstance(modalElement)
            if (modal) {
                modal.hide()
            }
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
        document.querySelectorAll(params.selector).forEach((e) => {
            e.innerHTML = params.value.trim()
        })
    });

    handler.registerAction('remove', (params) => {
        document.querySelectorAll(params.selector).forEach((e) => {
            e.remove()
        })
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
