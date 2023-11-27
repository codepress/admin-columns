import BaseSpecification from "./base-specification";
import Specification = AC.Specification.Specification;

export default class NotSpecification extends BaseSpecification {


    constructor(private specification: Specification) {
        super();
    }

    isSatisfiedBy(value: string): boolean {
        return !this.specification.isSatisfiedBy(value);
    }

}