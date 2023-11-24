import AggregateSpecification from "./aggregate-specification";

export default class AndSpecification extends AggregateSpecification {

    isSatisfiedBy(value: string): boolean {
        this.specifications.forEach(specification => {
            if (!specification.isSatisfiedBy(value)) {
                return false;
            }
        });

        return true;
    }

}