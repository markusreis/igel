import {selfOrClosestData} from "../utils/dom";

export const initPartnerToggle = () => {
    document.addEventListener('click', e => {
        const toggle = selfOrClosestData(e.target, 'action', 'toggle-partners')
        if (toggle) {
            togglePartners()
        }
    })
    window.addEventListener('resize', e => {
        const wrap = document.getElementById('partner-list')
        if (wrap) {
            const list = wrap.querySelector('.c-partners__list')
            list.dataset.loaded = 'false'
            list.removeAttribute('style')
            list.setAttribute('aria-expanded', 'false')
            document.querySelector('[data-action="toggle-partners"]').innerText = 'Alle zeigen'
        }
    })
}

function togglePartners() {
    const wrap = document.getElementById('partner-list')
    const list = wrap.querySelector('.c-partners__list')
    const isExpanded = list.getAttribute('aria-expanded') === 'true'
    list.style.height = list.clientHeight + 'px'
    list.dataset.loaded = 'true'

    if (isExpanded) {
        list.style.height = list.querySelector('.c-partners__el').clientHeight + 'px'
        list.setAttribute('aria-expanded', 'false')
        document.querySelector('[data-action="toggle-partners"]').innerText = 'Alle zeigen'
    } else {
        document.querySelector('[data-action="toggle-partners"]').innerText = 'Einklappen'
        list.setAttribute('aria-expanded', 'true')
        setTimeout(() => {
            list.style.height = list.querySelector('.c-partners__list__inner').clientHeight + 'px'
        }, 20)
    }
}