import {selfOrClosest} from "../utils/dom";

export const initAccordion = () => {
    document.addEventListener('click', e => {
        const node = selfOrClosest(e.target, 'c-accordion__el--has-content')
        let contentNode = selfOrClosest(e.target, 'c-accordion__content')
        if (node && !contentNode) {
            Array.from(node.closest('.c-accordion').querySelectorAll('.c-accordion__el')).forEach(n => {
                if (n !== node) {
                    n.setAttribute('aria-expanded', false)
                    const child = n.querySelector('.c-accordion__content')
                    child.setAttribute('aria-expanded', false)
                    child.style.height = '0px'
                }
            })

            contentNode = node.querySelector('.c-accordion__content')
            contentNode.style.height = contentNode.getAttribute('aria-expanded') === 'true'
                                       ? '0px'
                                       : contentNode.children[0].clientHeight + 'px'
            node.setAttribute('aria-expanded', contentNode.getAttribute('aria-expanded') !== 'true')
            contentNode.setAttribute('aria-expanded', contentNode.getAttribute('aria-expanded') !== 'true')
        }
    })

    document.addEventListener('resize', e => {
        Array.from(document.querySelectorAll('.c-accordion__content[aria-expnaded="true"]')).forEach(n => n.style.height = n.children[0].clientHeight + 'px')
    })
}