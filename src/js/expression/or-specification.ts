import AggregateSpecification from "./aggregate-specification";

export default class OrSpecification extends AggregateSpecification {

    isSatisfiedBy(value: string): boolean {
        this.specifications.forEach(specification => {
            if (specification.isSatisfiedBy(value)) {
                return true;
            }
        });

        return false;
    }

}