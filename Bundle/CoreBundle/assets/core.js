// --- Bootstrap
import {Tooltip as bsTooltip, Modal as bsModal, Offcanvas as bsOffcanvas, Popover as bsPopover} from 'bootstrap';
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

window.umbrella = {
    LANG: LANG,
    Translator : new Translator(LANG),
    Spinner : Spinner,
    ConfirmModal : ConfirmModal,
    Toast : Toast
}

// --- DataTable.js
import UmbrellaDataTable from './datatable/UmbrellaDataTable';
customElements.define('umbrella-datatable', UmbrellaDataTable);

// --- Forms
import UmbrellaSelect from './form/UmbrellaSelect';
import UmbrellaTag from './form/UmbrellaTag';
import UmbrellaCollection from './form/UmbrellaCollection';
import DatePicker from './form/DatePicker';
import PasswordTogglable from './form/PasswordTogglable';

customElements.define('umbrella-datepicker', DatePicker, {extends: 'input'});
customElements.define('umbrella-collection', UmbrellaCollection);
customElements.define('umbrella-select', UmbrellaSelect, {extends: 'select'});
customElements.define('umbrella-tag', UmbrellaTag, {extends: 'select'});
customElements.define('password-togglable', PasswordTogglable, {extends: 'div'});

// --- JsResponseHandler
import JsResponseHandler from './jsresponse/JsResponseHandler';
import configureHandler from './jsresponse/Configure'

const jsResponseHandler = new JsResponseHandler();
configureHandler(jsResponseHandler);

window.umbrella.jsResponseHandler = jsResponseHandler

// --- Bind some elements
import BindUtils from './utils/BindUtils';
BindUtils.enableAll();
