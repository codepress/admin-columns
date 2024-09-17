import OrSpecification from "./or-specification";
import AndSpecification from "./and-specification";
import NotSpecification from "./not-specification";
import ComparisonSpecification from "./comparison-specification";
import Specification = AC.Specification.Specification;
import Rule = AC.Specification.Rule;
import AggregateRule = AC.Specification.AggregateRule;
import ComparisonRule = AC.Specification.ComparisonRule;
import NotRule = AC.Specification.NotRule;
import NullSpecification from "./null-specification";


export default class RuleSpecificationMapper {

    static map(rule: Rule): Specification {
        switch (rule.type) {
            case 'null':
                return new NullSpecification();
            case 'or':
            case 'and':
                return this.createAggregate(rule as AggregateRule);
            case 'not':
                return new NotSpecification(this.map((rule as unknown as NotRule ).rule));
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