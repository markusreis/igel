import {toNode} from "../utils/dom";

export class EvaluationStep {
    constructor({branches = 'any', name, title, fields}) {
        this._branches = branches
        this._name = name
        this._title = title
        this._fields = fields

        this._dom = null

        this._app = null
    }

    isBranch(branch) {
        return this._branches === 'any' || this.branches.includes(branch)
    }

    get branches() {
        return this._branches;
    }

    get name() {
        return this._name;
    }

    get title() {
        return this._title;
    }

    get fields() {
        return this._fields;
    }

    get valid() {
        return !this._fields.some(f => !f.valid)
    }

    change(field) {
        this._dom.dataset.valid = this.valid
        this._app.change(field)
    }

    render(app) {
        this._app = app
        if (!this._dom) {

            const node = toNode(`
            <div class="c-evaluation__step c-evaluation__step--actual c-evaluation__step--${this._name}">
                <div class="c-evaluation__step__title">${this._title}</div>
            </div>
        `)

            const next = toNode(`
                <div class="c-evaluation__buttons">
                    <button type="submit" data-action="prev"><i class="button--before ig ig-arrow"></i><span class="c-evaluation__buttons__text">Zurück</span></button>
                    <button type="submit" data-action="next"><span class="c-evaluation__buttons__text">Weiter</span><i class="button--after ig ig-arrow"></i></button>
                </div>
            `)
            const inputs = toNode(`<div class="c-evaluation__step__inputs"></div>`)
            this._fields.forEach(f => inputs.append(f.render(this)))

            node.append(inputs)
            node.append(next)
            this._dom = node
        }

        return this._dom
    }
}

