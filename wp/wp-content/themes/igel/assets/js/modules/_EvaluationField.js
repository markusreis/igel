import {toNode} from "../utils/dom";

export class EvaluationField {

    constructor({type, options, label, name, onError, required = false, regex = null, defaultValue = ''}) {
        this._type = type
        this._options = options
        this._label = label
        this._defaultValue = defaultValue
        this._name = name
        this._regex = regex
        this._onError = onError
        this._required = required // for checkboxes only

        this._step = null

        this._dom = null
    }

    sanitize(str) {
        str = str.replace(/[^a-z0-9áéíóúñü \.,_-]/gim, "");
        return str.trim();
    }

    _initListener() {
        switch (this._type) {

            case 'checkbox':
                this._inputs[0].addEventListener('change', ev => this._step.change(this))
                break;

            case 'select':
            case 'radio':
                Array.from(this._inputs).forEach(e => {
                    e.addEventListener('change', ev => this._step.change(this))
                })
                break;

            case 'text':
            case 'number':
                this._inputs[0].addEventListener('input', ev => this._step.change(this))
                break;
        }
    }

    get valid() {
        switch (this._type) {

            case 'select':
            case 'radio':
                return this.value !== null

            case 'checkbox':
                return !this._required || this._inputs[0].checked

            case 'text':
            case 'number':
                const v = this.value
                if (v === '') {
                    return false
                }
                return !(!!this._regex && !this._regex.test(v));

        }
    }

    get valueName() {
        switch (this._type) {

            case 'checkbox':
                return this._inputs[0].checked ? 'Ja' : 'Nein'

            case 'select':
            case 'radio':
                let v = null
                Array.from(this._inputs).forEach(inp => {
                    if (inp.checked) {
                        v = inp.dataset.name
                    }
                })
                return v

            case 'text':
            case 'number':
                return this._inputs[0].value
        }
    }

    get value() {
        switch (this._type) {

            case 'checkbox':
                return this._inputs[0].checked

            case 'select':
            case 'radio':
                let v = null
                Array.from(this._inputs).forEach(inp => {
                    if (inp.checked) {
                        v = inp.value
                    }
                })
                return v

            case 'text':
            case 'number':
                return this._inputs[0].value
        }
    }

    render(step) {
        this._step = step
        if (!this._dom) {
            let html = ''
            switch (this._type) {
                case 'checkbox':
                    html = `
                    <div class="c-checkbox">
                        <input type="checkbox" name="${this._name}" id="${this._name}"/>
                        <label for="${this._name}"/>
                            ${this._label}
                        </label>
                    </div>`;
                    break;

                case 'select':
                case 'radio':
                    this._options.forEach(opt => {
                        const v = !!opt.value ? opt.value : this.sanitize(opt.name)
                        html += `
                    <div class="c-checkbox">
                        <input type="radio" name="${this._name}" data-name="${opt.name}" value="${v}" id="${this._name}${v}"/>
                        <label for="${this._name}${v}"/>
                            ${!!opt.icon ? `<i class="ig ig-${opt.icon}"></i><span>${opt.name}</span>` : opt.name}
                        </label>
                    </div>
                    `
                    })
                    break;

                case 'text':
                case 'number':
                    html = `
                <div class="input-wrap">
                    <input type="${this._type}" id="${this._name}" placeholder=" " value="${!!this._defaultValue ? this._defaultValue : ''}">
                    <label for="${this._name}">${this._label}</label>
                </div>
                `
                    break;
            }
            this._dom = toNode(`<div class="c-evaluation__input c-evaluation__input--${this._type}">${html}</div>`)
            this._inputs = this._dom.querySelectorAll('input')
            this._initListener()
        }
        return this._dom
    }
}
