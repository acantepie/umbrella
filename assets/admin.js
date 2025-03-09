import './scss/admin.scss'

import Translator from './translator/Translator';
import Spinner from './ui/Spinner'
import ConfirmModal from './ui/ConfirmModal'
import Toast from './ui/Toast'
import JsResponseHandler from './jsresponse/JsResponseHandler';
import configureHandler from './jsresponse/Configure'
import UmbrellaDataTable from './datatable/UmbrellaDataTable';
import UmbrellaSelect from './form/UmbrellaSelect';
import UmbrellaTag from './form/UmbrellaTag';
import UmbrellaCollection from './form/UmbrellaCollection';
import DatePicker from './form/DatePicker';
import PasswordTogglable from './form/PasswordTogglable';
import BindUtils from './utils/BindUtils';
import UmbrellaNotification from './UmbrellaNotification';
import UmbrellaSidebar from './UmbrellaSidebar';

const LANG = document.querySelector('html').getAttribute('lang')

window.umbrella = {
    LANG: LANG,
    Translator : new Translator(LANG),
    Spinner : Spinner,
    ConfirmModal : ConfirmModal,
    Toast : Toast
}

// --- DataTable.js
customElements.define('umbrella-datatable', UmbrellaDataTable);

// --- Forms
customElements.define('umbrella-datepicker', DatePicker, {extends: 'input'});
customElements.define('umbrella-collection', UmbrellaCollection);
customElements.define('umbrella-select', UmbrellaSelect, {extends: 'select'});
customElements.define('umbrella-tag', UmbrellaTag, {extends: 'select'});
customElements.define('password-togglable', PasswordTogglable, {extends: 'div'});

// --- Admin components
customElements.define('umbrella-notification', UmbrellaNotification, {extends: 'li'});
customElements.define('umbrella-sidebar', UmbrellaSidebar, {extends: 'nav'});

// --- JsResponseHandler
const jsResponseHandler = new JsResponseHandler();
configureHandler(jsResponseHandler);

window.umbrella.jsResponseHandler = jsResponseHandler

// --- Bind some elements
BindUtils.enableAll();
