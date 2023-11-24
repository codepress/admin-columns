import Specification from "./specification";
import BaseSpecification from "./base-specification";

export default class AndSpecification extends BaseSpecification {


    constructor(private specifications: Array<Specification>) {
        super();
    }

    isSatisfiedBy(value: string): boolean {
        this.specifications.forEach(specification => {
            if (!specification.isSatisfiedBy(value)) {
                return false;
            }
        });

        return true;
    }

}