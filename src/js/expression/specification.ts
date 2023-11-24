export default interface Specification {

    isSatisfiedBy(value: string): boolean;

    andSpecification(specification: Specification): Specification;

    orSpecification(specification: Specification): Specification;

    not(): Specification;

}