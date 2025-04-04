import Spec from "./";
import Specification = AC.Specification.Specification;

export default class AndSpecification implements Specification {

    constructor(private specifications: Array<Specification>) {
    }

    andSpecification(specification: Specification): Specification {
        return new AndSpecification([specification, this]);
    }

    not(): Specification {
        return new Spec.Not(this);
    }

    orSpecification(specification: Specification): Specification {
        return new Spec.Or([specification, this]);
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