export const initContactForms = () => {
    document.querySelectorAll('[data-js="contact-form"]').forEach(e => new ContactForm(e))
}

class ContactForm {
    constructor(wrapper) {

        if (wrapper.dataset.init === 'y') return;
        wrapper.dataset.init = 'y'

        this._dom = {
            form: wrapper,
            inputs: Array.from(wrapper.querySelectorAll('input')),
        }

        this._regexDefinitions = {
            email: new RegExp(/(?:[a-z0-9!#$%&'*+/=?^_`{|}~-]+(?:\.[a-z0-9!#$%&'*+/=?^_`{|}~-]+)*|"(?:[\x01-\x08\x0b\x0c\x0e-\x1f\x21\x23-\x5b\x5d-\x7f]|\\[\x01-\x09\x0b\x0c\x0e-\x7f])*")@(?:(?:[a-z0-9](?:[a-z0-9-]*[a-z0-9])?\.)+[a-z0-9](?:[a-z0-9-]*[a-z0-9])?|\[(?:(?:25[0-5]|2[0-4][0-9]|[01]?[0-9][0-9]?)\.){3}(?:25[0-5]|2[0-4][0-9]|[01]?[0-9][0-9]?|[a-z0-9-]*[a-z0-9]:(?:[\x01-\x08\x0b\x0c\x0e-\x1f\x21-\x5a\x53-\x7f]|\\[\x01-\x09\x0b\x0c\x0e-\x7f])+)\])/),
            tel: new RegExp(/[0-9\(\)\-\+ \/]{4,}/),
            checkbox: new RegExp(/on/),
            text: new RegExp(/(.{1,})/),
            textarea: new RegExp(/(.{30,})/),
            hidden: new RegExp(/.*/),
        }

        this._errorNoticeDefinitions = {
            email: 'Bitte geben Sie eine gültige E-Mail Adresse an',
            tel: 'Bitte geben Sie eine gültige Telefonnummer an',
            text: 'Das Feld darf nicht leer sein',
            checkbox: 'Bitte bestätigen Sie die Checkbox',
            textarea: 'Die Nachricht sollte mindestens 30 Zeichen enthalten',
        }

        this._state = {
            disableErrors: false,
            loading: false,
            isDefaultContact: this._dom.form.dataset.jsContactForm === 'default'
        }

        if (this._state.isDefaultContact) {
            this._dom.inputs.push(wrapper.querySelector('textarea'))
        }

        this._initSubmitListener()
        this._initInputListeners()
    }

    reset() {
        this._dom.inputs.forEach(e => {
            this.hideState(e)
            e.value = ''
        })
    }

    _initSubmitListener() {
        this._dom.form.addEventListener('submit', e => {
            e.preventDefault()
            if (this.validate() && !this._state.loading) {
                this._state.loading = true
                const node = window.showToast({msg: 'Nachricht wird gesendet', type: 'loading'})
                setTimeout(() => {
                    const post = this.sendPost()
                    post.then(result => {
                        node.hide()

                        console.log(result)

                        setTimeout(() => {
                            window.showToast({
                                msg: 'Vielen Dank für Ihre Anfrage. Die Nachricht wurde erfolgreich übermittelt.',
                                type: 'success',
                                timeout: 4000
                            })
                        }, 200)
                        setTimeout(() => {
                            this._state.loading = false
                            this.reset()
                        }, 4000)
                    })
                        .catch(error => {
                            setTimeout(() => {
                                this._state.loading = false
                                node.hide()
                                window.showToast({
                                    msg: 'Etwas ist schief gelaufen. Bitte schicken Sie uns eine E-Mail.',
                                    type: 'error',
                                    timeout: 4000
                                })
                            }, 200)
                        })
                }, 300)
            }
        })
    }

    sendPost() {
        const formData = new FormData();
        this._dom.inputs.forEach(node => formData.append(node.getAttribute('name'), node.value))
        const url = igelData.apiBase + (this._state.isDefaultContact ? 'contact' : 'inserat');

        return fetch(url, {
            method: 'POST',
            body: formData,
        })
            .then(response => {
                if (response.status !== 200) {
                    throw 'Invalid response'
                }
                return response.json()
            });
    }

    _initInputListeners() {
        this._dom.inputs.forEach(node => {
            node.addEventListener('blur', () => {
                node.classList.add('-blurred')
                if (node.classList.contains('-changed') || node.classList.contains('-valid') || node.classList.contains('-invalid')) {
                    if (!this.validateSingle(node).isValid) {
                        this.showError(node)
                    } else {
                        this.hideError(node)
                    }
                }
            })
            node.addEventListener('input', () => {
                node.classList.add('-changed')
                if (node.classList.contains('-blurred') || node.classList.contains('-valid') || node.classList.contains('-invalid')) {
                    if (!this.validateSingle(node).isValid) {
                        this.showError(node)
                    } else {
                        this.hideError(node)
                    }
                }
            })
        })
    }

    validate() {
        let formIsValid = true;
        let errorKeys = []
        this._dom.inputs.forEach((e) => {
            const res = this.validateSingle(e);
            if (!res.isValid) {
                formIsValid = false;
                this.showError(e)
                errorKeys.push(res.key)
            } else {
                this.hideError(e)
            }
        })
        if (!this._state.disableErrors) {
            this._state.disableErrors = true
            errorKeys.forEach(key => this.displayErrorMessage(key))
            setTimeout(() => {
                this._state.disableErrors = false
            }, 2400)
        }
        return formIsValid;
    }

    validateSingle(input) {
        switch (input.tagName) {

            case 'INPUT':
                return {
                    isValid: this._regexDefinitions[input.getAttribute('type')].test(input.value),
                    key: input.getAttribute('type')
                };

            case 'TEXTAREA':
                return {
                    isValid: this._regexDefinitions['textarea'].test(input.value),
                    key: 'textarea'
                };

            default:
                window.alert('Missing validateSingle input tag')
                return {isValid: false, key: 'missing'};
        }
    }

    displayErrorMessage(messageKey) {
        window.showToast({msg: this._errorNoticeDefinitions[messageKey], type: 'error', timeout: 2000})
    }

    showError(node) {
        const wrap = node.closest('.input-wrap');
        if (wrap) {
            wrap.classList.add('-invalid')
            wrap.classList.remove('-valid')
        }
        node.classList.remove('-valid')
        node.classList.add('-invalid')
    }

    hideError(node) {
        const wrap = node.closest('.input-wrap');
        if (wrap) {
            wrap.classList.remove('-invalid')
            wrap.classList.add('-valid')
        }
        node.classList.add('-valid')
        node.classList.remove('-invalid')
    }

    hideState(node) {
        const wrap = node.closest('.input-wrap');
        if (wrap) {
            wrap.classList.remove('-invalid')
            wrap.classList.remove('-valid')
            wrap.classList.remove('-blurred')
            wrap.classList.remove('-changed')
        }
        node.classList.remove('-valid')
        node.classList.remove('-invalid')
        node.classList.remove('-blurred')
        node.classList.remove('-changed')
    }
}