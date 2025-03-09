import SimpleBar from 'simplebar';

export default class UmbrellaSidebar extends HTMLElement {

    constructor() {
        super();
        this.sidebarNav = this.querySelector('.sidebar-nav')
        this.sidebarToggle = document.querySelector('.js-sidebar-toggle')
        this.searchInput = this.querySelector('.sidebar-search input')
    }

    connectedCallback() {
        this.initializeSimplebar()
        this.initializeSidebarCollapse()
        this.initializeSearch()
    }

    initializeSearch() {
        if (this.searchInput) {
            this.searchInput.addEventListener('keyup', (e) => {
                this.search(e.currentTarget.value)
            })
        }
    }

    initializeSimplebar() {

        if (this.sidebarNav) {
            // fix invalid sidenav height
            this.sidebarNav.style.minHeight = `calc(100% - ${this.sidebarNav.offsetTop}px)`;

            const simpleBar = new SimpleBar(this.sidebarNav);

            /* Recalculate simplebar on sidebar dropdown toggle */
            const sidebarDropdowns = this.sidebarNav.querySelectorAll('[data-bs-parent]');

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
        if (this.sidebarNav && this.sidebarToggle) {
            this.sidebarToggle.addEventListener('click', this.toggle.bind(this))
        }
    }

    toggle() {
        document.body.classList.toggle('sidebar-collapsed');

        const transitionendListener = () => {
            window.dispatchEvent(new Event('resize'))
            this.removeEventListener('transitionend', transitionendListener)
        }

        this.addEventListener('transitionend', transitionendListener);
    }

    search(search) {
        search = search.toLowerCase().trim()

        // show all
        if ('' === search) {
            this.sidebarNav.querySelectorAll('.sidebar-item[data-search]').forEach(e => e.hidden = false)
            return
        }

        // matches
        const matches = []
        this.querySelectorAll('.sidebar-item[data-search]').forEach(e => {
            e.hidden = true

            if (e.dataset.search.toLowerCase().trim().includes(search)) {
                let match = e
                do {
                    matches.push(match)
                    match = match.parentNode.closest('.sidebar-nav .sidebar-item[data-search]')
                } while (match)
            }
        })

        matches.forEach(e => e.hidden = false)
    }
}
