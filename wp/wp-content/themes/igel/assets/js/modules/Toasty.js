export class Toast {

    constructor(opt = {msg: 'No message', type: 'error', timeout: 1000}) {
        this.options = opt
        this.node = this.createHtml()
    }

    createHtml() {
        const div = document.createElement('div')
        div.classList.add('c-toasts__el', 'c-toasts__el--' + this.options.type)
        div.innerText = this.options.msg
        const loader = document.createElement('span')
        loader.classList.add('c-toasts__el__bar')
        loader.style.transition = 'width ' + this.options.timeout + 'ms linear';
        div.append(loader)
        return div;
    }

    show() {
        setTimeout(() => {
            this.node.classList.add('c-toasts__el--active')
            if (this.options.type !== 'loading' && this.options.timeout > 0) {
                setTimeout(() => {
                    this.hide()
                }, this.options.timeout)
            }
        }, 10)
    }

    hide() {
        this.node.classList.add('c-toasts__el--leaving')
        setTimeout(() => {
            this.node.remove()
        }, 400)
    }
}

export class ToastManager {

    constructor() {
        this._dom = {}
        this._toasts = []
        this.createWrapper()
    }

    createWrapper() {
        const wrap = document.createElement('div')
        wrap.classList.add('c-toasts')
        const inner = document.createElement('div')
        inner.classList.add('c-toasts__inner')
        wrap.append(inner)
        document.body.append(wrap)
        this._dom.wrapper = wrap
        this._dom.inner = inner
    }

    show(toast) {
        this._toasts.push(toast)
        this._dom.inner.append(toast.node)
        toast.show()
    }
}