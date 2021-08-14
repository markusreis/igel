import {preload} from "./preload";
import {Toast, ToastManager} from "./modules/Toasty";
import {initBarba} from "./modules/Barba";

import {Agents, Buy, Homepage, NewBuild, NewBuilds, Realty, Sell} from './pages/all'
import {initPartnerToggle} from "./handlers/partnertoggle";

(function () {

    class App {
        constructor() {

            this.createPages()
            initPartnerToggle()
            this.loadCurrentPage()

            initBarba(this)

            document.documentElement.classList.add('-preloaded')
        }

        createPages() {
            this.pages = {
                Agents   : new Agents({app: this}),
                Buy      : new Buy({app: this}),
                Homepage : new Homepage({app: this}),
                NewBuild : new NewBuild({app: this}),
                NewBuilds: new NewBuilds({app: this}),
                Realty   : new Realty({app: this}),
                Sell     : new Sell({app: this})
            }
        }

        loadCurrentPage() {
            const pageName = document.getElementById('pagename').dataset.name
            document.documentElement.dataset.page = pageName
            return this.pages[pageName].create()
        }

        leaveCurrentPage() {
            return this.pages[document.getElementById('pagename').dataset.name].leave()
        }
    }

    let toastManager = null

    window.showToast = function (opt = {msg: 'No message', type: 'error', timeout: 1000}) {
        if (!toastManager) {
            toastManager = new ToastManager()
        }
        const toast = new Toast(opt)
        toastManager.show(toast)
        return toast
    }

    document.addEventListener('DOMContentLoaded', function () {
        preload(({p}) => {console.log(p)}).then(() => new App())
    })
})()