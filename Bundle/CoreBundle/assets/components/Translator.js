const TRANS = {
    en: {
        'row_selected': {
             '1' : '%c% item selected. <a href data-onclick="unselect-all">Clear selection</a>',
            '_': '%c% items selected. <a href data-onclick="unselect-all">Clear selection</a>'
        },
        'cancel': 'Cancel',
        'confirm': 'Confirm',
        'disconnected_error': 'You are disconnected. Refresh page to login',
        'loading_data_error': 'Unable to load data',
        'unable_to_contact_server': 'Unable to contact server',
        'error_occured' : 'An error occured.'
    },

    fr: {
        'row_selected': {
            '1' : '%c% élément sélectionnée. <a href data-onclick="unselect-all">Effacer la sélection</a>',
            '_': '%c% éléments sélectionnées. <a href data-onclick="unselect-all">Effacer la sélection</a>',
        },
        'cancel': 'Annuler',
        'confirm': 'Confirmer',
        'disconnected_error': 'Vous n\'etes plus connecté. Veuillez rafraichir la page pour vous authentifier',
        'loading_data_error': 'Impossible de charger les données',
        'unable_to_contact_server': 'Impossible de contacter le serveur',
        'error_occured' : 'Une erreur est survenue.'
    },
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