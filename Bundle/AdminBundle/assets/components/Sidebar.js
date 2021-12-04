import SimpleBar from 'simplebar';

export default class Sidebar extends HTMLElement {

    constructor() {
        super();
        this.sidebarContent = this.querySelector('.sidebar-content')
        this.sidebarToggle = document.querySelector('.js-sidebar-toggle')
    }

    connectedCallback() {
        this.initializeSimplebar()
        this.initializeSidebarCollapse()
    }

    initializeSimplebar() {

        if (this.sidebarContent) {
            const simpleBar = new SimpleBar(this.sidebarContent);

            /* Recalculate simplebar on sidebar dropdown toggle */
            const sidebarDropdowns = this.sidebarContent.querySelectorAll('.js-sidebar [data-bs-parent]');

            sidebarDropdowns.forEach(link => {

                link.addEventListener('shown.bs.collapse', () => {
                    simpleBar.recalculate();
                });

                link.addEventListener('hidden.bs.collapse', () => {
                    simpleBar.recalculate();
                });
            });
        }
    }

    initializeSidebarCollapse() {
        if (this.sidebarContent && this.sidebarToggle) {
            this.sidebarToggle.addEventListener('click', () => {
                this.classList.toggle('collapsed');

                this.addEventListener('transitionend', () => {
                    window.dispatchEvent(new Event('resize'));
                });
            });
        }
    }
}