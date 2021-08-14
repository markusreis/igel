import {Page} from "./Page";
import {initEvaluation} from "../modules/Evaluation";

export class Sell extends Page {

    constructor(props) {
        super(props)
    }

    create() {
        setTimeout(() => {
            initEvaluation()
        }, 300)
        return super.create()
    }
}