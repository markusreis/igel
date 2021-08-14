const lerp = function (value1, value2, amount) {
    amount = amount < 0 ? 0 : amount;
    amount = amount > 1 ? 1 : amount;
    return value1 + (value2 - value1) * amount;
}

const toNode = (str) => {
    const d = document.createElement('div')
    d.innerHTML = str
    return d.children[0]
}

export {
    lerp,
    toNode
};