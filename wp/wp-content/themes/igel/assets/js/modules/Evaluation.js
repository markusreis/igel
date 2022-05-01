import {evaluationSteps} from "./_EvaluationConfig";
import {selfOrClosestData} from "../utils/dom";
import {gsap} from "gsap";

export const initEvaluation = () => {
    document.querySelectorAll('.c-evaluation').forEach(e => new Evaluation(e))
}

export class Evaluation {
    constructor(wrapper) {
        this._dom = {
            wrapper: wrapper,
            steps: wrapper.querySelector('.c-evaluation__steps')
        }

        this._state = {
            branch: null, // 'house', 'apartment', 'property'
            step: -1,
            formValues: {},
            initalError: wrapper.dataset.config === 'evaluationRequest'
                ? 'Bitte geben Sie Ihre Adresse an'
                : 'Bitte geben Sie eine Region an'
        }

        this._conf = {
            steps: evaluationSteps[wrapper.dataset.config]
        }

        this._initListeners()
    }


    _initListeners() {
        this._dom.wrapper.addEventListener('submit', e => {
            e.preventDefault()
        })
        this._dom.wrapper.addEventListener('click', e => {
            const next = selfOrClosestData(e.target, 'action', 'next')
            if (next) {
                this.nextStep()
            }
            const prev = selfOrClosestData(e.target, 'action', 'prev')
            if (prev) {
                this.prevStep()
            }
        })
    }

    get valid() {
        if (this._state.step === -1) {
            if (this._dom.wrapper.querySelector('[data-field="initial"]').value !== '') {
                return true
            }
            window.showToast({
                msg: this._state.initalError,
                type: 'error',
                timeout: 3000
            })
        } else {
            if (this.step.valid) {
                return true
            }
            this.step.fields.forEach(f => {
                if (!f.valid) {
                    window.showToast({
                        msg: f._onError,
                        type: 'error',
                        timeout: 3000
                    })
                }
            })
        }
        return false
    }

    change(evaluationField) {
        if (evaluationField._name === 'eval-type') {
            this._state.branch = evaluationField.value
        }
        this._state.formValues[evaluationField._name] = evaluationField.value
    }

    nextStep() {
        if (this.valid) {
            if (this._state.step < this.steps.length) {
                this.step = this._state.step + 1
                if (Cookiebot?.consent?.statistics) {

                    dataLayer.push({'stepName': this.step.title});
                    dataLayer.push({'stepNumber': this._state.step + 1});

                    gtag('event', this._dom.wrapper.dataset.config);
                }
            }
        }
    }

    prevStep() {
        this.step = Math.max(-1, this._state.step - 1)
    }

    finalize() {
        const node = window.showToast({msg: 'Nachricht wird gesendet', type: 'loading'})

        const fields = [
            {
                'type': 'headline',
                'title': this._dom.wrapper.dataset.config === 'evalRequest'
                    ? 'Neue "Immobilie bewerten" Anfrage'
                    : 'Neue "Suchauftrag" Anfage',
            }
        ]
        this.steps.forEach((s, i) => {

            fields.push({
                title: s._title,
                type: 'sectionTitle'
            })

            s.fields.forEach(f => {
                fields.push({
                    title: !!f._label ? f._label : s._title,
                    value: f.valueName,
                    type: 'fieldValue'
                })
            })
        })

        const url = igelData.apiBase + 'eval';

        setTimeout(() => {

            fetch(url, {
                method: 'POST',
                body: JSON.stringify(fields),
                headers: {
                    "Content-Type": "application/json"
                }
            })
                .then(response => {
                    node.hide()
                    if (response.status !== 200) {

                        window.showToast({
                            msg: 'Wir bitten um Entschulding. Etwas ist schief gelaufen. Bitte kontaktieren Sie uns persönlich.',
                            type: 'error',
                            timeout: 5000
                        })
                    } else {
                        response.json()

                        window.showToast({
                            msg: 'Vielen Dank! Wir haben Ihre Anfrage erhalten und werden uns schnellstmöglich bei Ihnen melden.',
                            type: 'success',
                            timeout: 5000,
                        })

                        gtag('event', `${this._dom.wrapper.dataset.config}Success`);

                        setTimeout(() => {
                            this.step = -1
                            this._dom.wrapper.querySelector('.c-evaluation__step--initial input').value = ''
                        }, 500)
                    }
                });
        }, 500)
    }

    get step() {
        return this.getStep(this._state.step)
    }

    set step(no) {
        const max = this.steps.length
        no = Math.min(max, no)
        if (no !== this._state.step) {
            const prev = this._state.step
            this._state.step = no
            if (no === max) {
                this.finalize()
            } else {
                this.animate(prev, no)
            }
        }
    }

    animate(prevStep, newStep) {

        this._dom.steps.style.height = this._dom.steps.clientHeight + 'px'

        let step, node;
        if (newStep > -1) {
            step = this.steps[newStep]
            node = step.render(this)
            this._dom.steps.append(node)
        } else {
            node = this._dom.wrapper.querySelector('.c-evaluation__step--initial')
        }

        const current = this._dom.wrapper.querySelector('[data-active="true"]')

        const durationPer = 0.5
        const offset = 30
        gsap.to(this._dom.steps, {height: node.clientHeight, duration: durationPer})
        gsap.to(current, {opacity: 0, x: prevStep < newStep ? -offset : offset, duration: durationPer})
        gsap.fromTo(node, {opacity: 0, x: prevStep > newStep ? -offset : offset}, {
            opacity: 1,
            x: 0,
            duration: durationPer,
            delay: durationPer / 3
        })

        current.dataset.active = "false"
        node.dataset.active = "true"

    }

    getStep(no) {
        const steps = this.steps
        return !!steps[no] ? steps[no] : null
    }

    get steps() {
        return this._state.branch === null ? this._conf.steps : this._conf.steps.filter(e => e.isBranch(this._state.branch))
    }
}