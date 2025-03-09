export default class UmbrellaNotification extends HTMLLIElement {

    constructor() {
        super()

        this.refreshUrl = this.dataset.refreshUrl
        this.pollInterval = this.dataset.pollInterval // s
        this.refreshXhr = null
    }

    connectedCallback() {
        this.addEventListener('shown.bs.dropdown', () => {
            this._refresh(this.pollInterval >= 1) // refresh only if pollInterval is 1s or more
        })
    }

    /**
     * Refresh Notifications
     */
    _refresh(poll = true) {
        if (this.refreshXhr) {
            this.refreshXhr.abort()
        }

        if (this._isOpen()) {
            $.get(this.refreshUrl, (response) => {
                this._renderList(response)

                if (poll) {
                    setTimeout(() => {
                        this._refresh()
                    }, this.pollInterval * 1000)
                }
            })
        }
    }

    /**
     * Render list of notifications
     */
    _renderList(response) {
        const list = this.querySelector('.notification-items')
        list.innerHTML = ''

        if (response.html) {
            list.innerHTML = response.html
            return
        }

        if (response.notifications) {
            for (const n of response.notifications) {
                list.innerHTML += n.html
            }
        }
    }

    _isOpen() {
        return this.querySelector('.dropdown-menu').classList.contains('show')
    }
}
