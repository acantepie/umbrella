const TRANS = {
    en: {
        'cancel': 'Cancel',
        'confirm': 'Confirm',
        'disconnected_error': 'You are disconnected. Refresh page to login',
        'loading_data_error': 'Unable to load data',
        'unable_to_contact_server': 'Unable to contact server',
        'error_occured' : 'An error occured.'
    },

    fr: {
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

    getTranslations(lang = null) {
        if (null === lang) {
            lang = this.lang;
        }
        return lang in TRANS ? TRANS[lang] : {};
    }

    trans(key, lang = null) {
        const translations = this.getTranslations(lang);
        return key in translations ? translations[key] : key;
    }
}