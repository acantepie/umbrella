import {Tooltip as bsTooltip, Modal as bsModal, Offcanvas as bsOffcanvas, Popover as bsPopover} from 'bootstrap';

import UmbrellaDataTable from './datatable/UmbrellaDataTable';

import UmbrellaSelect from './form/UmbrellaSelect';
import UmbrellaTag from './form/UmbrellaTag';
import UmbrellaCollection from './form/UmbrellaCollection';
import DatePicker from './form/DatePicker';
import PasswordTogglable from './form/PasswordTogglable';
import JsResponseHandler from './jsresponse/JsResponseHandler';
import configureHandler from './jsresponse/Configure'
import Notification from './Notification';
import Sidebar from './Sidebar';

import './scss/admin.scss'

// --- Bootstrap

window.bootstrap = {
    Tooltip : bsTooltip,
    Modal: bsModal,
    Offcanvas: bsOffcanvas,
    Popover: bsPopover
}

// --- Umbrella
import Translator from './translator/Translator';
import Spinner from './ui/Spinner'
import ConfirmModal from './ui/ConfirmModal'
import Toast from './ui/Toast'

const LANG = document.querySelector('html').getAttribute('lang')

const jsResponseHandler = new JsResponseHandler();
configureHandler(jsResponseHandler);

window.umbrellaAdmin = {
    LANG: LANG,
    Translator : new Translator(LANG),
    Spinner : Spinner,
    ConfirmModal : ConfirmModal,
    Toast : Toast,
    jsResponseHandler : jsResponseHandler
}

// --- custom Elements
customElements.define('umbrella-datatable', UmbrellaDataTable);
customElements.define('umbrella-datepicker', DatePicker, {extends: 'input'});
customElements.define('umbrella-collection', UmbrellaCollection);
customElements.define('umbrella-select', UmbrellaSelect, {extends: 'select'});
customElements.define('umbrella-tag', UmbrellaTag, {extends: 'select'});
customElements.define('password-togglable', PasswordTogglable, {extends: 'div'});
customElements.define('umbrella-notification', Notification, {extends: 'li'});
customElements.define('umbrella-sidebar', Sidebar, {extends: 'nav'});

// --- Bind some elements
import BindUtils from './utils/BindUtils';
BindUtils.enableAll();


