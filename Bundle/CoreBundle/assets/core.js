// --- Jquery
import './jquery/JQuery';

// --- Bootstrap
import {Tooltip as bsTooltip, Modal as bsModal, Popover as bsPopover} from 'bootstrap';
window.bootstrap = {
    Tooltip : bsTooltip,
    Modal: bsModal,
    Popover: bsPopover
}

// --- Umbrella
import Translator from './components/Translator';
import Spinner from './components/Spinner'
import ConfirmModal from './components/ConfirmModal'
import Toast from './components/Toast'

const LANG = document.querySelector('html').getAttribute('lang')

window.umbrella = {
    LANG: LANG,
    Translator : new Translator(LANG),
    Spinner : Spinner,
    ConfirmModal : ConfirmModal,
    Toast : Toast
}

// --- DataTable.js
import 'datatables.net';

import 'datatables.net-bs4';
import 'datatables.net-rowreorder';
import 'jquery-treetable'

import Toolbar from './components/Toolbar';
import DataTable from './components/DataTable';
customElements.define('umbrella-toolbar', Toolbar);
customElements.define('umbrella-datatable', DataTable);

// --- Forms
import Select2 from './components/Select2';
import UmbrellaCollection from './components/UmbrellaCollection';
import DatePicker from './components/DatePicker';
import PasswordTogglable from './components/PasswordTogglable';

customElements.define('umbrella-datepicker', DatePicker, {extends: 'input'});
customElements.define('umbrella-collection', UmbrellaCollection);
customElements.define('umbrella-select2', Select2, {extends: 'select'});
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