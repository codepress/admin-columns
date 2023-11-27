import NotSpecification from "./not-specification";
import AndSpecification from "./and-specification";
import Specification = AC.Specification.Specification;

export default class OrSpecification implements Specification {

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
        return this.specifications.some(specification => {
            return specification.isSatisfiedBy(value);
        });
    }

}