declare namespace AC.Specification {

    interface Specification {

        isSatisfiedBy(value: string): boolean;

        andSpecification(specification: Specification): Specification;

        orSpecification(specification: Specification): Specification;

        not(): Specification;

    }

    interface Rule {
        type: string
        fact: string
        operator: string
    }

    interface AggregateRule extends Rule {
        rules: Array<Rule>;
    }

    interface ComparisonRule extends Rule {
        fact: string;
        operator: string;
    }

}
