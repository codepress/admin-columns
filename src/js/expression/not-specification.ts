import Specification from "./specification";
import BaseSpecification from "./base-specification";

export default  class NotSpecification extends BaseSpecification{


    constructor(private specification: Specification) {
        super();
    }

    isSatisfiedBy(value: string): boolean {
        return ! this.specification.isSatisfiedBy(value);
    }

}