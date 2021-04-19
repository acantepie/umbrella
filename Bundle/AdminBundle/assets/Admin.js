// App
import  "umbrella_core/Core";

import "metismenu";
import "simplebar"

// components
import Sidebar from "./components/Sidebar";
import Notification from "./components/Notification";

customElements.define('umbrella-sidebar', Sidebar);
customElements.define('umbrella-notification', Notification, {extends: 'li'});

// Hyper layout
const $body = $('body');
const $window = $(window);

const adjustLayout = () => {
    // in case of small size, add class enlarge to have minimal menu
    if ($window.width() >= 767 && $window.width() <= 1028) {
        $body.attr('data-leftbar-compact-mode', 'condensed');
    } else {
        $body.attr('data-leftbar-compact-mode', false);
    }
};

adjustLayout();

$window.on('resize', (e) => {
    e.preventDefault();
    adjustLayout();
});