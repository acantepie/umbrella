// --- Translator
import Translator from "./Translator";
window.LANG = document.querySelector('html').getAttribute('lang');
window.Translator = new Translator(LANG);

// --- Jquery
import './jquery/JQuery';
import './jquery/SerializeFormToFormData';
import './jquery/SerializeFormToJson';

// --- Bootstrap
import 'bootstrap';

// --- DataTable.js
import 'datatables.net';

import 'datatables.net-bs4';
import 'datatables.net-rowreorder';
import 'datatables.net-fixedheader';
import 'jquery-treetable'

import Toolbar from "./components/Toolbar";
import DataTable from "./components/DataTable";
customElements.define('umbrella-toolbar', Toolbar);
customElements.define('umbrella-datatable', DataTable);

// --- Toast
import toastr from 'toastr';
window.toastr = toastr;

import Toast from "./components/Toast";
customElements.define('umbrella-toast', Toast);

// --- Forms (select2)
import 'select2/dist/js/select2.full';
import 'select2/dist/js/i18n/fr';

import Select2 from "./components/Select2";
import AsyncSelect2 from "./components/AsyncSelect2";
import TagsInput from "./components/TagsInput";

customElements.define('select-2', Select2, {extends: 'select'});
customElements.define('async-select-2', AsyncSelect2, {extends: 'select'});
customElements.define('tags-select-2', TagsInput, {extends: 'select'});

// --- Forms (other)
import UmbrellaFile from "./components/UmbrellaFile";
import UmbrellaCollection from "./components/UmbrellaCollection";
import DatePicker from "./components/DatePicker";

customElements.define('date-picker', DatePicker, {extends: 'input'});
customElements.define('umbrella-file', UmbrellaFile);
customElements.define('umbrella-collection', UmbrellaCollection);

// --- JsResponseHandler
import JsResponseHandler from './jsresponse/JsResponseHandler';
window.jsResponseHandler = new JsResponseHandler();

import OpenModal from "./jsresponse/action/OpenModal";
import CloseModal from "./jsresponse/action/CloseModal";
import Eval from "./jsresponse/action/Eval";
import Redirect from "./jsresponse/action/Redirect";
import Reload from "./jsresponse/action/Reload";
import RemoveHtml from "./jsresponse/action/RemoveHtml";
import UpdateHtml from "./jsresponse/action/UpdateHtml";
import ReloadTable from "./jsresponse/action/ReloadTable";
import ShowToast from "./jsresponse/action/ShowToast";

jsResponseHandler.registerAction('toast', new ShowToast());
jsResponseHandler.registerAction('reload_table', new ReloadTable());
jsResponseHandler.registerAction('open_modal', new OpenModal());
jsResponseHandler.registerAction('close_modal', new CloseModal());
jsResponseHandler.registerAction('eval', new Eval());
jsResponseHandler.registerAction('redirect', new Redirect());
jsResponseHandler.registerAction('reload', new Reload());
jsResponseHandler.registerAction('update', new UpdateHtml());
jsResponseHandler.registerAction('remove', new RemoveHtml());

// --- Bind some elements
import BindUtils from "./utils/BindUtils";
BindUtils.bindAll();