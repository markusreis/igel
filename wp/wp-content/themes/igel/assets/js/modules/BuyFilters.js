import {toNode} from "../utils/dom";
import {gsap} from "gsap";

export class BuyFilters {
    constructor() {
        const form = document.querySelector('.c-buy-filter')
        this._dom = {
            form  : form,
            button: form.querySelector('button[type="submit"]')
        }

        this._state = {
            loading: false
        }
        this._initListener()
    }

    _initListener() {
        this._dom.form.addEventListener('submit', e => {
            e.preventDefault()
            this.submit()
        })
        this._dom.button.addEventListener('click', e => {
            e.preventDefault()
            this.submit()
        })
    }

    getValues() {
        const selects = Array.from(this._dom.form.querySelectorAll('select'))
        const inputs = Array.from(this._dom.form.querySelectorAll('input'))
        const data = []
        for (let i = 0; i < selects.length; i++) {
            if (!!selects[i].options[selects[i].selectedIndex].value && selects[i].options[selects[i].selectedIndex].value !== 'all') {
                data.push({
                              name : selects[i].getAttribute('name'),
                              value: selects[i].options[selects[i].selectedIndex].value
                          })
            }
        }
        for (let i = 0; i < inputs.length; i++) {
            if (!!inputs[i].value) {
                data.push({name: inputs[i].getAttribute('name'), value: inputs[i].value})
            }
        }
        return data
    }

    async submit() {

        if (!this._state.loading) {
            const immoList = document.querySelector('.c-immo-list')
            document.querySelector('.c-hero__box').classList.add('-loading')
            this._state.loading = true

            gsap.to(immoList.querySelectorAll('a'), {
                opacity : 0,
                stagger : 0.025,
                duration: 0.3
            })

            let url = this._dom.form.getAttribute('action')
            this.getValues().forEach((field, i) => {
                url += (i === 0 ? '?' : '&') + field.name + '=' + field.value
            })
            const res = await fetch(url, {headers: {'X-Ajax': 'internal'}})
            const html = await res.text()
            const section = toNode(html)
            this._state.loading = false

            immoList.parentElement.replaceChild(section, immoList)

            gsap.fromTo(section.querySelectorAll('a'), {
                opacity: 0,
            }, {
                            opacity : 1,
                            stagger : 0.05,
                            duration: 0.75
                        })

            document.querySelector('.c-hero__box').classList.remove('-loading')
        }
    }
}