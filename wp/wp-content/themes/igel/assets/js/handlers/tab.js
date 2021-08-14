import {selfOrClosest} from "../utils/dom";

export class Tabs {
    constructor(wrapper) {
        this._dom = {
            wrapper: wrapper,
        }

        this._state = {
            drag: {
                // TODO: FACTOR IN MIN THUMB WIDTH
                minThumbWidth: 200,
                active       : false,
                lastOffset   : -1,
            }
        }
        this._initClickListener()
        this._initResizeListener()
        this._initScrollListener()
        this._initThumbListener()

        this.setThumbPosition()
    }

    _getNode(key, className) {
        if (!this._dom[key]) {
            this._dom[key] = document.querySelector(className)
        }
        return this._dom[key]
    }

    get wrapperNode() {
        return this._dom.wrapper
    }

    get contentNode() {
        return this._getNode('content', '.c-tabs__content')
    }

    get markerNode() {
        return this._getNode('marker', '.c-tabs__marker')
    }

    get thumbNode() {
        return this._getNode('thumb', '.c-tabs__scrollbar__thumb')
    }

    get scrollbarNode() {
        return this._getNode('scrollbar', '.c-tabs__scrollbar')
    }

    setThumbPosition() {
        const visiblePercentage = (this._dom.wrapper.clientWidth / this._dom.wrapper.scrollWidth) * 100

        if (visiblePercentage < 99.99) {
            this.scrollbarNode.style.display = 'block'
            const maxScroll = this._dom.wrapper.scrollWidth - this._dom.wrapper.clientWidth
            const desiredThumbWidth = this.scrollbarNode.clientWidth - maxScroll
            const thumbWidth = Math.max(this._state.drag.minThumbWidth, desiredThumbWidth)
            const scrollPos = this._dom.wrapper.scrollLeft
            this.thumbNode.style.left = scrollPos + 'px'
            this.thumbNode.style.width = thumbWidth + 'px'
        } else {
            this.scrollbarNode.style.display = 'none'
        }
    }

    _initThumbListener() {
        this.scrollbarNode.addEventListener('click', e => {
            const offset = e.clientX - this.scrollbarNode.offsetLeft
            const offsetP = Math.max(0, Math.min(1, (offset / this.scrollbarNode.clientWidth)))
            const maxScroll = this._dom.wrapper.scrollWidth - this._dom.wrapper.clientWidth
            this._dom.wrapper.scrollTo({
                                           top     : 0,
                                           left    : maxScroll * offsetP,
                                           behavior: 'smooth'
                                       })
        })
        this.scrollbarNode.addEventListener('mousedown', e => {
            this._startDrag(e)
        })
        this.scrollbarNode.addEventListener('touchstart', e => {
            this._startDrag(e)
        })
        document.addEventListener('mouseup', e => {
            this._endDrag()
        })
        document.addEventListener('touchend', e => {
            this._endDrag()
        })
        document.addEventListener('mousemove', e => {
            this._drag(e)
        })
        document.addEventListener('touchmove', e => {
            this._drag(e)
        })
    }

    _startDrag(e) {
        this._state.drag.lastOffset = this.getEventPositionX(e) - this.scrollbarNode.offsetLeft
        this._state.drag.active = true
    }

    _endDrag() {
        this._state.drag.active = false
        this._state.drag.lastOffset = -1
    }

    _drag(e) {
        if (this._state.drag.active) {
            this.pauseEvent(e)
            const newOffset = (this.getEventPositionX(e) - this.scrollbarNode.offsetLeft)
            if (this._state.drag.lastOffset !== -1) {
                const diff = (this._state.drag.lastOffset - newOffset) * 2
                const currentScroll = this.thumbNode.offsetLeft
                const maxScroll = this.scrollbarNode.clientWidth - this.thumbNode.clientWidth
                this._dom.wrapper.scrollTo({
                                               top : 0,
                                               left: Math.max(0, Math.min(maxScroll, currentScroll - diff)),
                                           })
            }
            this._state.drag.lastOffset = newOffset
        }
    }

    pauseEvent(e) {
        if (e.stopPropagation) e.stopPropagation();
        if (e.preventDefault) e.preventDefault();
        e.cancelBubble = true;
        e.returnValue = false;
        return false;
    }


    getEventPositionX(e) {
        if (e.type === 'touchstart' || e.type === 'touchmove' || e.type === 'touchend' || e.type === 'touchcancel') {
            var touch = e.touches[0] || e.changedTouches[0];
            return touch.pageX;
        } else if (e.type === 'mousedown' || e.type === 'mouseup' || e.type === 'mousemove' || e.type === 'mouseover' || e.type === 'mouseout' || e.type === 'mouseenter' || e.type === 'mouseleave') {
            return e.clientX;
        }
    }

    _initClickListener() {
        this.wrapperNode.addEventListener('click', e => {
            const node = selfOrClosest(e.target, 'c-tabs__tab')
            if (node && !node.classList.contains('c-tabs__tab--active')) {
                const activeNode = this.wrapperNode.querySelector('.c-tabs__tab--active')
                activeNode.classList.remove('c-tabs__tab--active')
                node.classList.add('c-tabs__tab--active')
                this.moveTabHighlightToActive()
                this.show(node.dataset.jsTarget, activeNode.dataset.jsTarget)
            }
        })
    }

    _initScrollListener() {
        this.wrapperNode.addEventListener('scroll', e => {
            this.setThumbPosition()
        })
    }

    _initResizeListener() {
        window.addEventListener('resize', () => {
            this.contentNode.style.height = 'auto'
            this.moveTabHighlightToActive()
            this.setThumbPosition()
        })
    }

    moveTabHighlightToActive() {
        const node = this.wrapperNode.querySelector('.c-tabs__tab--active')
        this.markerNode.style.width = this.markerNode.clientWidth + 'px'
        this.markerNode.remove()
        this.wrapperNode.prepend(this.markerNode)
        this.markerNode.style.width = node.clientWidth + 'px'
        this.markerNode.style.left = node.offsetLeft + 'px'
    }

    show(slug, previousSlug) {
        

        const active = this.contentNode.querySelector('.c-tabs__content__inner--active')
        const passive = this.contentNode.querySelector('.c-tabs__content__inner[data-slug="' + slug + '"]')

        this.contentNode.style.height = this.contentNode.clientHeight + 'px'

        setTimeout(() => {
            this.contentNode.style.height = passive.clientHeight + 'px';

            active.classList.toggle('c-tabs__content__inner--active')
            active.classList.toggle('c-tabs__content__inner--passive')

            setTimeout(() => {
                passive.classList.toggle('c-tabs__content__inner--active')
                passive.classList.toggle('c-tabs__content__inner--passive')
            }, 100)
        })
    }
}