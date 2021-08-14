//Returns true if it is a DOM node
function isNode(o) {
    return (
        typeof Node === "object" ? o instanceof Node :
        o && typeof o === "object" && typeof o.nodeType === "number" && typeof o.nodeName === "string"
    );
}

//Returns true if it is a DOM element
function isElement(o) {
    return (
        typeof HTMLElement === "object" ? o instanceof HTMLElement : //DOM2
        o && typeof o === "object" && o !== null && o.nodeType === 1 && typeof o.nodeName === "string"
    );
}

const getCumulativeElementOffset = function (el) {
    var _x = 0;
    var _y = 0;
    while (el && !isNaN(el.offsetLeft) && !isNaN(el.offsetTop)) {
        _x += el.offsetLeft;
        _y += el.offsetTop;
        el = el.offsetParent;
    }
    return {top: _y, left: _x};
};

/**
 * Convert string to domnode
 *
 * @param str
 * @returns {Element}
 */
function toNode(str) {
    const d = document.createElement('div')
    d.innerHTML = str
    return d.children[0]
}

function selfOrClosest(node, className) {
    return node.classList.contains(className) ? node : (
        node.closest(`.${className}`) ? node.closest(`.${className}`) : null
    )
}

function selfOrClosestData(node, key, val = null) {
    if (!!val) {
        return node.dataset[key] === val ? node : (
            node.closest(`[data-${key}="${val}"]`) ? node.closest(`[data-${key}="${val}"]`) : null
        )
    }
    return !!node.dataset.key
           ? node
           : node.closest(`[data-${key}]`) ? node.closest(`[data-${key}]`) : null
}

function superToggle(node, ...classes) {
    classes.forEach(c => node.classList.toggle(c))
}

export {isNode, isElement, toNode, selfOrClosest, selfOrClosestData, superToggle, getCumulativeElementOffset}
