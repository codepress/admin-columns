import Specification from "./specification";
import AndSpecification from "./and-specification";
import NotSpecification from "./not-specification";
import OrSpecification from "./or-specification";

export default abstract class AggregateSpecification implements Specification {

    constructor(protected specifications: Array<Specification>) {
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

    abstract isSatisfiedBy(value: string): boolean;

}