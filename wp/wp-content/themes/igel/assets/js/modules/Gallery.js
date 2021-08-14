import {getCumulativeElementOffset, selfOrClosestData, toNode} from "../utils/dom";

export const initGallery = () => {
    new Gallery(document.querySelector('.c-gallery__modal'))
}

export class Gallery {

    constructor(wrapper) {

        const modal = this.renderWrapper();
        this._dom = {
            modal     : modal,
            toggle    : document.querySelector('[data-action="open-gallery"]'),
            modalInner: modal.querySelector('.c-gallery__modal__inner'),
            previews  : modal.querySelector('.c-gallery__modal__previews'),
        }

        this._state = {
            realty     : !!this._dom.toggle.dataset.r ? parseInt(this._dom.toggle.dataset.r) : false,
            post       : !!this._dom.toggle.dataset.p ? parseInt(this._dom.toggle.dataset.p) : false,
            loaded     : false,
            loading    : false,
            blockScroll: false,
            gallery    : [],
            active     : false,
            activeIndex: 0,
            total      : 0
        }

        this._dom.modal.removeAttribute('r')

        this._initListener()
    }

    _initListener() {
        this._dom.toggle.addEventListener('click', this.toggle.bind(this))
    }

    init() {
        this.fetch()

        window.addEventListener('resize', this.setMainImageSize.bind(this))
        document.addEventListener('wheel', e => this.preventScroll(e), {passive: false})
        document.addEventListener('DOMMouseScroll', e => this.preventScroll(e), {passive: false})
        document.addEventListener('touchmove', e => this.preventScroll(e), {passive: false})
        document.addEventListener('keydown', e => {
            if (!this._state.active) {
                return;
            }
            switch (e.keyCode) {
                case 40: // DOWN ARROW
                case 39: // ARROW RIGHT
                    e.preventDefault()
                    this.next()
                    break;
                case 38: // UP ARROW
                case 37: // ARROW LEFT
                    e.preventDefault()
                    this.prev()
                    break;
                case 32: // SPACE
                    e.preventDefault()
                    this.next()
                    break;
                case 27: // ESC
                    e.preventDefault()
                    this.close()
                    break;
            }
        })
        this._dom.modal.querySelector('.c-gallery__modal__overlay').addEventListener('click', this.close.bind(this))
        this._dom.modal.addEventListener('click', e => {
            const node = selfOrClosestData(e.target, 'action')
            if (node) {
                e.stopPropagation()
                this[node.dataset.action]()
            }
        })
    }

    preventScroll(e) {
        if (this._state.active) {
            e.preventDefault()
            e.stopPropagation()
        }
    }

    toggle() {
        if (!this._state.loaded && !this._state.loading) {
            this.init()
        }
        if (!this._state.active) {
            this.open()
        } else {
            this.close()
        }
    }

    next() {
        this.show(this._state.activeIndex === this._state.total - 1 ? 0 : this._state.activeIndex + 1)
    }

    prev() {
        this.show(this._state.activeIndex === 0 ? this._state.total - 1 : this._state.activeIndex - 1)
    }

    show(i) {
        this._dom.previews.querySelector('[data-active="true"]').dataset.active = false
        this._dom.previews.children[i].dataset.active = true
        this._state.activeIndex = i

        const img = this._dom.previews.children[i].firstChild.cloneNode(true)
        img.style.opacity = 0
        img.style.transition = 'opacity .3s ease'
        const img2 = img.cloneNode(true)

        const modalPic = this._dom.modalInner.querySelector('picture')
        const heroPic = document.querySelector('.c-hero__bg')
        modalPic.append(img)
        heroPic.append(img2)
        setTimeout(() => {
            img.style.opacity = 1
            img2.style.opacity = 1
        }, 5)
        setTimeout(() => {
            modalPic.querySelector('img').remove()
            heroPic.querySelector('img').remove()
        }, 300)
    }

    open() {
        this._state.active = true
        this._dom.modal.dataset.active = 'true'
        this._dom.modalInner.innerHTML = ''

        const heroBg = document.querySelector('.c-hero__bg')
        const initialImage = heroBg.cloneNode(true)

        const {top, left} = getCumulativeElementOffset(heroBg);
        initialImage.style.top = `${top - window.scrollY}px`
        initialImage.style.left = `${left}px`
        initialImage.style.width = `${heroBg.clientWidth}px`
        initialImage.style.height = `${heroBg.clientHeight}px`
        this._dom.modalInner.append(initialImage)
        setTimeout(this.setMainImageSize.bind(this), 5)
    }

    setMainImageSize() {
        const activeImage = this._dom.modalInner.querySelector('picture')

        let width, height, top, left;
        if (window.innerHeight / window.innerWidth < 0.66) {
            height = Math.min(1600, window.innerHeight - 130)
            width = height * (1 / 0.66)
            top = (window.innerHeight - 60 - height) / 2
            left = (window.innerWidth - width) / 2
        } else {
            width = Math.min(1600, window.innerWidth - 50)
            height = width * 0.66
            top = (window.innerHeight - height) / 2
            left = (window.innerWidth - width) / 2
        }
        activeImage.style.top = `${top}px`
        activeImage.style.left = `${left}px`
        activeImage.style.width = `${width}px`
        activeImage.style.height = `${height}px`
    }

    close() {
        this._dom.modal.dataset.active = 'false'

        const activeImage = this._dom.modalInner.querySelector('picture')
        const heroBg = document.querySelector('.c-hero__bg')
        const {top, left} = getCumulativeElementOffset(heroBg);
        activeImage.style.top = `${top - window.scrollY}px`
        activeImage.style.left = `${left}px`
        activeImage.style.width = `${heroBg.clientWidth}px`
        activeImage.style.height = `${heroBg.clientHeight}px`
        activeImage.style.opacity = '0'

        setTimeout(() => {
            this._state.active = false
        }, 200)
    }

    async fetch() {
        if (!this._state.loaded && !this._state.loading) {
            this._state.loading = true
            const param = this._state.realty !== false ? 'r=' + this._state.realty : 'p=' + this._state.post
            this._state.gallery = await fetch(igelData.apiBase + 'realty-gallery?' + param)
            this._state.gallery = await this._state.gallery.json()
            this._state.loaded = true
            this._state.loading = false

            this._state.total = this._state.gallery.length
            this._state.gallery.forEach((g, i) => {
                const node = toNode(g)
                node.addEventListener('click', () => this.show(i))
                node.dataset.active = i === 0
                this._dom.previews.append(node)
            })
        }
        return this._state.gallery
    }

    renderWrapper() {
        const wrap = toNode(`
        <div class="c-gallery__modal">
            <div class="c-gallery__modal__overlay"></div>
            <div class="c-gallery__modal__inner">

            </div>
            <div class="c-gallery__modal__previews">

            </div>
            <div class="c-gallery__modal__buttons" data-action="close">
                <div class="button" data-action="close" id="modal-close">
                    ZURÜCK
                </div>
                <div class="c-gallery__modal__arrow-buttons">
                    <div class="button" data-action="prev">
                        <i class="button--after ig ig-arrow"></i>
                    </div>
                    <div class="button" data-action="next">
                        <i class="button--after ig ig-arrow"></i>
                    </div>
                </div>
            </div>
        </div>
        `)
        document.body.append(wrap)
        return wrap
    }
}

