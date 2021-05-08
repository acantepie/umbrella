// App
import  "umbrella_core/Core";

import "simplebar"

// components
import Notification from "./components/Notification";
import Password from "./components/Password";
import Layout from "./Layout";

customElements.define('umbrella-notification', Notification, {extends: 'li'});
customElements.define('umbrella-password', Password, {extends: 'div'});

Layout.init();