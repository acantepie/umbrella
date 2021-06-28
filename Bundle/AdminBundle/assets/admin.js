// App
import './scss/admin.scss'

import  'umbrella_core/core';
import 'simplebar'

// components
import Notification from './components/Notification';
import Password from './components/Password';
import Menu from './components/Menu';
import Layout from './Layout';

customElements.define('umbrella-notification', Notification, {extends: 'li'});
customElements.define('umbrella-password', Password, {extends: 'div'});
customElements.define('umbrella-menu', Menu, {extends: 'div'});

Layout.init();