import BaseSpecification from "./base-specification";

export default class ComparisonSpecification extends BaseSpecification {

    constructor(private fact: string, private operator: string) {
        super();
    }

    isSatisfiedBy(value: string): boolean {
        switch (this.operator) {
            case 'equal':
                return value === this.fact;
        }

        return false;
    }

}