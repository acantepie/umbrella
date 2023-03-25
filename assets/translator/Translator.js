const TRANS = {
    en: {
        'row_selected': {
            '1' : '%c% row selected. <a href class="unselectall-handler">Clear selection</a>',
            '_': '%c% rows selected. <a href class="unselectall-handler">Clear selection</a>'
        },
        'cancel': 'Cancel',
        'confirm': 'Confirm',
        'unauthorized_error': 'You are disconnected. Refresh page to login',
        'forbidden_error': 'You are not allowed to perform this action',
        'notfound_error': 'Unable to contact server',
        'other_error' : 'An error occured',
        'loading_data_error': 'Unable to load data',
        'no_results': 'No results found'
    },

    fr: {
        'row_selected': {
            '1' : '%c% ligne sélectionnée. <a href class="unselectall-handler">Effacer la sélection</a>',
            '_': '%c% lignes sélectionnées. <a href class="unselectall-handler">Effacer la sélection</a>',
        },
        'cancel': 'Annuler',
        'confirm': 'Confirmer',
        'unauthorized_error': 'Vous n\'êtes plus connecté. Veuillez rafraichir la page pour vous authentifier',
        'forbidden_error': 'Vous n\'êtes pas autorisé à effectuer cette action',
        'notfound_error': 'Impossible de contacter le serveur',
        'other_error': 'Une erreur est survenue',
        'loading_data_error': 'Impossible de charger les données',
        'no_results': 'Aucun résultat trouvé'
    },

    ru: {
        'row_selected': {
            '_': '%c% выбрано. <a href class="unselectall-handler">Сбросить</a>'
        },
        'cancel': 'Отменить',
        'confirm': 'Подтвердить',
        'unauthorized_error': 'Вы отключены, обновите страницу',
        'forbidden_error': 'У Вас нет прав на выполнение этого действия',
        'notfound_error': 'Не удаётся подключиться к серверу',
        'other_error' : 'Произошла ошибка',
        'loading_data_error': 'Не удаётся загрузить данные'
    }
}


export default class Translator {
    constructor(lang) {
        this.lang = lang;
    }

    getLang() {
        return this.lang
    }

    getTranslations(lang = null) {
        if (null === lang) {
            lang = this.lang;
        }
        return lang in TRANS ? TRANS[lang] : {};
    }

    trans(key, params = {}, lang = null) {
        const translations = this.getTranslations(lang);

        let translation = key in translations ? translations[key] : key

        if (typeof translation === 'object') {
            const ks = Object.keys(params);
            const k = ks.length > 0 ? params[ks[0]] : '_'

            if (k in translation) {
                translation = translation[k]
            } else if ('_' in translation) {
                translation = translation['_']
            } else {
                throw new Error('Invalid translation', translation)
            }
        }

        for (const [k, v] of Object.entries(params)) {
            translation = translation.replace(k, v)
        }


        return translation;
    }
}
