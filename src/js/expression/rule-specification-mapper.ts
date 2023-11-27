import Specification from "./specification";
import OrSpecification from "./or-specification";
import AndSpecification from "./and-specification";
import NotSpecification from "./not-specification";
import ComparisonSpecification from "./comparison-specification";

interface Rule {
    type: string;
}

interface AggregateRule extends Rule {
    rules: Array<Rule>;
}

interface ComparisonRule extends Rule {
    fact: string;
    operator: string;
}

export default class RuleSpecificationMapper {

    static map(rule: Rule): Specification {
        console.log( rule );
        switch (rule.type) {
            case 'or':
            case 'and':
                return this.createAggregate(rule as AggregateRule);
            case 'not':
                return new NotSpecification(this.map(rule));
            case 'string_comparison':
            case 'comparison':
                return this.createComparison(rule as ComparisonRule)
        }

        throw Error;
    }

    private static createComparison(rule: ComparisonRule): Specification {
        return new ComparisonSpecification(rule.fact, rule.operator);
    }

    private static createAggregate(aggregateRule: AggregateRule): Specification {
        let specifications: Specification[] = [];

        aggregateRule.rules.forEach(rule => specifications.push(this.map(rule)));

        switch (aggregateRule.type) {
            case 'or':
                return new OrSpecification(specifications);
            case 'and':
                return new AndSpecification(specifications);
        }

        throw Error;
    }

}