import {Page} from "./Page";
import {initGallery} from "../modules/Gallery";
import {initContactForms} from "../handlers/contact";

export class Realty extends Page {

    constructor(props) {
        super(props)
    }

    create() {
        setTimeout(() => {
            initGallery()
            initContactForms()
        }, 300)
        return super.create()
    }

    leave() {
        const modal = document.querySelector('.c-gallery__modal')
        if (!!modal) {
            modal.remove()
        }
        return super.leave();
    }
}