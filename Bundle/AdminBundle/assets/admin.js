import './scss/admin.scss'
import 'umbrella_core/core';

// components
import Notification from './components/Notification';
customElements.define('umbrella-notification', Notification, {extends: 'li'});

import Sidebar from './components/Sidebar';
customElements.define('umbrella-sidebar', Sidebar, {extends: 'nav'});
