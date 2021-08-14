import {Page} from "./Page";
import {BuyFilters} from "../modules/BuyFilters";

export class Buy extends Page {

    constructor(props) {
        super(props)
    }

    create() {
        setTimeout(() => {
            new BuyFilters()
        }, 300)
        return super.create();
    }
}