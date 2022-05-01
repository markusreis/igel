import {Page} from "./Page";
import {initEvaluation} from "../modules/Evaluation";
import {selfOrClosest} from "../utils/dom";

export class Sell extends Page {

    constructor(props) {
        super(props)
    }

    create() {

        document.querySelector('.c-services').addEventListener('click', e => {
            const target = selfOrClosest(e.target, 'c-services__el')

            if (target) {
                const isExpanded = target.dataset.expanded === 'true'

                if (isExpanded && !selfOrClosest(e.target, 'c-services__title')) return

                target.querySelector('.c-services__text').style.height = isExpanded
                    ? 0
                    : `${target.querySelector('.c-services__text__inner').clientHeight}px`

                target.dataset.expanded = !isExpanded
            }
        })

        return super.create()
    }
}