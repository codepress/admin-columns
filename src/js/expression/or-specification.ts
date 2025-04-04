import Spec from "./";
import Specification = AC.Specification.Specification;

export default class OrSpecification implements Specification {

    constructor(private specifications: Array<Specification>) {
    }

    andSpecification(specification: Specification): Specification {
        return new Spec.And([specification, this]);
    }

    not(): Specification {
        return new Spec.Not(this);
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