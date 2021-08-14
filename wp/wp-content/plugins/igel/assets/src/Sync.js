export class Sync {
    constructor() {
        const syncButton = document.getElementById('igel-sync-button')

        if (!syncButton) {
            return;
        }

        this._dom = {
            button     : syncButton,
            progressBar: document.getElementById('sync-progress'),
            immoCounter: document.getElementById('sync-immo-counter'),
            userCounter: document.getElementById('sync-user-counter'),
            lastUpdate : document.getElementById('sync-last-update'),
        }

        this._state = {
            loading   : false,
            toDownload: {
                immo       : parseInt(this._dom.immoCounter.innerText),
                user       : parseInt(this._dom.userCounter.innerText),
                total      : parseInt(this._dom.immoCounter.innerText) + parseInt(this._dom.userCounter.innerText),
                didDownload: 0,
            }
        }

        this._config = {
            buttonDefaultText: this._dom.button.innerText,
            token            : igelAdminData.token,
            apiBase          : igelAdminData.baseUrl
        }

        this._dom.button.addEventListener('click', e => this.run())
    }

    async run() {
        if (!this._state.loading) {
            this._state.loading = true

            this._dom.button.style.background = '#aaa'
            document.documentElement.style.cursor = 'progress'
            this._dom.button.style.cursor = 'progress'

            await this.prepare()
        }
    }

    error(e, url) {
        console.log(e)
        console.log(url)
        this._dom.button.innerText = 'Fehler! Sorry! Bitte Programmierer kontaktieren!'
        this._dom.button.style.background = '#b63838'
        this._dom.button.style.borderColor = '#b63838'
        document.documentElement.style.cursor = 'initial'
        this._dom.button.style.cursor = 'pointer'
    }

    async _fetch(url) {
        let body
        try {
            const res = await fetch(url).catch(e => this.error(e, url))
            if (res.status !== 200) {
                this.error(res, url)
                return false
            }
            body = await res.json()
        } catch (e) {
            this.error(e, url)
            return false
        }
        return body
    }

    async prepare() {
        this._dom.button.innerText = 'Sync wird vorbereitet'

        const body = await this._fetch(`${this._config.apiBase}sync/prepare?api_token=${this._config.token}`)
        if (body === false) {
            return;
        }

        this.flashNumber(this._dom.immoCounter, body.realtyToDownload.length)
        this.flashNumber(this._dom.userCounter, body.userToDownload.length)

        this._state.toDownload.immo = body.realtyToDownload.length
        this._state.toDownload.user = body.userToDownload.length
        this._state.toDownload.total = this._state.toDownload.immo + this._state.toDownload.user

        if (this._state.toDownload.immo > 0 || this._state.toDownload.user > 0) {
            setTimeout(() => this.downloadNextImage(), 50)
        } else {
            this.finalize()
        }
    }

    async downloadNextImage() {
        this._dom.button.innerText = 'Bilder werden heruntergeladen...'

        const body = await this._fetch(`${this._config.apiBase}sync/download-media?api_token=${this._config.token}`)
        if (body === false) {
            return;
        }

        const toDownloadImmo = body.realtyToDownload.length
        const toDownloadUser = body.userToDownload.length

        if (this._state.toDownload.immo !== toDownloadImmo) {
            this._state.toDownload.immo = toDownloadImmo
            this.flashNumber(this._dom.immoCounter, toDownloadImmo)
        }
        if (this._state.toDownload.user !== toDownloadUser) {
            this._state.toDownload.user = toDownloadUser
            this.flashNumber(this._dom.userCounter, toDownloadUser)
        }

        this._state.toDownload.didDownload += 1

        if (this._state.toDownload.total > 0) {
            const p = Math.max(0, Math.min(1, this._state.toDownload.didDownload / this._state.toDownload.total))
            this._dom.progressBar.style.width = (p * 100) + '%'
        }

        if (this._state.toDownload.immo > 0 || this._state.toDownload.user > 0) {
            setTimeout(() => this.downloadNextImage(), 50)
        } else {
            this.finalize()
        }
    }

    finalize() {
        this._dom.button.innerText = 'Das hat funktioniert!'
        this._dom.button.style.background = '#317f33'
        document.documentElement.style.cursor = 'initial'
        this._dom.button.style.cursor = 'pointer'
        this._dom.lastUpdate.innerText = 'Gerade eben...'
        this._state.toDownload.total = 0
        this._state.toDownload.didDownload = 0
        setTimeout(() => {
            this._dom.button.style.background = '#2271b1'
            this._dom.button.innerText = this._config.buttonDefaultText
            this._dom.progressBar.style.display = 'none'
            this._dom.progressBar.style.widht = '0%'
            setTimeout(() => {
                this._dom.progressBar.style.display = 'block'
                this._dom.progressBar.style.widht = '0%'
            }, 200)
        }, 1000)
    }

    flashNumber(node, no, success = true) {
        node.style.background = success ? '#badea7' : '#f69191'
        node.innerText = no
        setTimeout(() => node.style.background = 'transparent', 250)
    }
}