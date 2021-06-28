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

customElements.define('umbrella-datepicker', DatePicker, {extends: 'input'});
customElements.define('umbrella-collection', UmbrellaCollection);
customElements.define('umbrella-select2', Select2, {extends: 'select'});


// --- JsResponseHandler
import JsResponseHandler from './jsresponse/JsResponseHandler';
const jsResponseHandler = new JsResponseHandler();

import ShowModal from './jsresponse/action/ShowModal';
import CloseModal from './jsresponse/action/CloseModal';
import Eval from './jsresponse/action/Eval';
import Redirect from './jsresponse/action/Redirect';
import Reload from './jsresponse/action/Reload';
import RemoveHtml from './jsresponse/action/RemoveHtml';
import UpdateHtml from './jsresponse/action/UpdateHtml';
import CallWebComponent from './jsresponse/action/CallWebComponent';
import ShowToast from './jsresponse/action/ShowToast';
import Download from './jsresponse/action/Download';

jsResponseHandler.registerAction('show_toast', new ShowToast());
jsResponseHandler.registerAction('show_modal', new ShowModal());
jsResponseHandler.registerAction('close_modal', new CloseModal());
jsResponseHandler.registerAction('eval', new Eval());
jsResponseHandler.registerAction('redirect', new Redirect());
jsResponseHandler.registerAction('reload', new Reload());
jsResponseHandler.registerAction('update', new UpdateHtml());
jsResponseHandler.registerAction('remove', new RemoveHtml());
jsResponseHandler.registerAction('call_webcomponent', new CallWebComponent());
jsResponseHandler.registerAction('download', new Download());

window.umbrella.jsResponseHandler = jsResponseHandler

// --- Bind some elements
import BindUtils from './utils/BindUtils';
BindUtils.enableAll();