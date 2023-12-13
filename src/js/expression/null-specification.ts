import BaseSpecification from "./base-specification";

export default class NullSpecification extends BaseSpecification {

    constructor() {
        super();
    }

    isSatisfiedBy(value: string): boolean {
        return true;
    }

}