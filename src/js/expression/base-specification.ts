import Spec from "./";
import Specification = AC.Specification.Specification;

export default abstract class BaseSpecification implements Specification {

    andSpecification(specification: Specification): Specification {
        return new Spec.And([specification, this]);
    }

    not(): Specification {
        return new Spec.Not(this);
    }

    orSpecification(specification: Specification): Specification {
        return new Spec.Or([specification, this]);
    }

    abstract isSatisfiedBy(value: string): boolean;

}