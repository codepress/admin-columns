import AndSpecification from "./and-specification";
import NotSpecification from "./not-specification";
import OrSpecification from "./or-specification";
import Specification = AC.Specification.Specification;

export default abstract class BaseSpecification implements Specification {

    andSpecification(specification: Specification): Specification {
        return new AndSpecification([specification, this]);
    }

    not(): Specification {
        return new NotSpecification(this);
    }

    orSpecification(specification: Specification): Specification {
        return new OrSpecification([specification, this]);
    }

    abstract isSatisfiedBy(value: string): boolean;

}