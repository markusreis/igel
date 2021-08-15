import {preload} from "./preload";
import {Toast, ToastManager} from "./modules/Toasty";
import {initBarba} from "./modules/Barba";

import {Agents, Buy, Homepage, NewBuild, NewBuilds, Realty, Sell} from './pages/all'
import {initPartnerToggle} from "./handlers/partnertoggle";
import {Page} from "./pages/Page";
import {getCumulativeElementOffset, selfOrClosestData} from "./utils/dom";

(function () {

    class App {
        constructor() {

            this.createPages()
            initPartnerToggle()
            this.loadCurrentPage()

            this._initScrollClickActions()

            initBarba(this)

            document.documentElement.classList.add('-preloaded')
        }

        createPages() {
            this.pages = {
                Page     : new Page({app: this}),
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
            const pageInfoBox = document.getElementById('pagename')
            const pageName = !!pageInfoBox ? pageInfoBox.dataset.name : 'Page'
            document.documentElement.dataset.page = pageName
            return this.pages[pageName].create()
        }

        leaveCurrentPage() {
            const pageInfoBox = document.getElementById('pagename')
            const pageName = !!pageInfoBox ? pageInfoBox.dataset.name : 'Page'
            return this.pages[pageName].leave()
        }

        _initScrollClickActions() {
            document.addEventListener('click', e => {
                const scrollAction = selfOrClosestData(e.target, 'scrollto')
                if (scrollAction) {
                    const target = document.querySelector('[data-scroll-target="' + scrollAction.dataset.scrollto + '"]')
                    if (target) {
                        const {top} = getCumulativeElementOffset(target)
                        window.scrollTo({
                                            top     : top,
                                            left    : 0,
                                            behavior: 'smooth'
                                        })
                    }
                }
            })
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