import BaseSpecification from "./base-specification";
import Specification from "./specification";

export default abstract class AggregateSpecification extends BaseSpecification {

    protected constructor(protected specifications: Array<Specification>) {
        super();
    }

}