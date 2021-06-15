class Layout {

    constructor() {
        this.body = document.querySelector('body');
        this.menuBtn = document.querySelector('.button-menu-mobile')

        this.adjustLayout = this.adjustLayout.bind(this)
        this.handleMenuBtnClick = this.handleMenuBtnClick.bind(this)
    }

    init() {
        window.addEventListener('resize', this.adjustLayout)

        if (this.menuBtn) {
            this.menuBtn.addEventListener('click', this.handleMenuBtnClick)
        }
    }

    adjustLayout() {
        if (window.innerWidth >= 767 && window.innerWidth <= 1028) {
            this.body.setAttribute('data-leftbar-compact-mode', 'condensed');
        } else {
            this.body.removeAttribute('data-leftbar-compact-mode')
        }
    }

    handleMenuBtnClick() {
        this.body.classList.toggle('sidebar-enable');

        if (window.innerWidth >= 576) {
            if (this.body.getAttribute('data-leftbar-compact-mode') === 'condensed') {
                this.body.removeAttribute('data-leftbar-compact-mode')
            } else {
                this.body.setAttribute('data-leftbar-compact-mode', 'condensed');
            }
        }
    }

}

export default new Layout();