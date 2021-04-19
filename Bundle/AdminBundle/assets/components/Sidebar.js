export default class Sidebar extends HTMLElement {
    constructor() {
        super();

        this.$view = $(this).find('.metismenu');
    }

    connectedCallback() {
        this.$view.metisMenu();

        $(document).on('click', '.button-menu-mobile', (e) => {
            e.preventDefault();
            $('body').toggleClass('sidebar-enable');
        });
    }

}