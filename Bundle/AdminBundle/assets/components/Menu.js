export default class Menu extends HTMLDivElement {
    constructor() {
        super();
        this.searchInput = this.querySelector('.menu-search-input')
    }

    connectedCallback() {
        if (this.searchInput) {
            this.searchInput.addEventListener('keyup', (e) => {
                this.search(e.currentTarget.value)
            })
        }
    }

    search(search) {
        search = search.toLowerCase().trim()

        // show all
        if ('' === search) {
            this.querySelectorAll('.menu-link, .side-nav-title').forEach(e => e.hidden = false)
            return
        }

        // hide all
        this.querySelectorAll('.menu-link, .side-nav-title').forEach(e => e.hidden = true)

        // show found
        this.querySelectorAll('.menu-link').forEach((e) => {
            if (e.textContent.toLowerCase().includes(search)) {
                this._showMenuLinkAndParent(e)
            }
        })
    }


    _showMenuLinkAndParent(link) {
        let parentItem = null

        link.hidden = false
        let item = link.closest('li.side-nav-item')

        if (!item) {
            return
        }

        do {
            parentItem = item.parentNode.closest('li.side-nav-item')
            if (!parentItem) {
                break
            }

            item = parentItem

            link = parentItem.querySelector('a.menu-link')
            if (!link) {
                break
            }

            link.hidden = false
        } while (true)


        if (item) {
            const titleItem = this._findMenuTitleItem(item)
            if (titleItem) {
                titleItem.hidden = false
            }
        }

    }

    _findMenuTitleItem(menuItem) {
        let sibling = menuItem.previousSibling;

        while (sibling) {
            if (sibling.matches && sibling.matches('li.side-nav-title')) {
                return sibling;
            }
            sibling = sibling.previousSibling
        }
        return null;
    }


}