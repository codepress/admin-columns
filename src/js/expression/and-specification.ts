import Specification from "./specification";
import NotSpecification from "./not-specification";
import OrSpecification from "./or-specification";

export default class AndSpecification implements Specification {

    constructor(private specifications: Array<Specification>) {
    }

    andSpecification(specification: Specification): Specification {
        return new AndSpecification([specification, this]);
    }

    not(): Specification {
        return new NotSpecification(this);
    }

    orSpecification(specification: Specification): Specification {
        return new OrSpecification([specification, this]);
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