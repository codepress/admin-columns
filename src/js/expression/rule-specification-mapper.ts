import Spec from "./";
import Specification = AC.Specification.Specification;
import AcSpecification = AC.Specification;

export default class RuleSpecificationMapper {

    static map(rule: AcSpecification.Rule): Specification {
        switch (rule.specification) {
            case 'null':
                return new Spec.Null();
            case 'or':
            case 'and':
                return this.createAggregate(rule as AcSpecification.AggregateRule);
            case 'not':
                return new Spec.Not(this.map((rule as unknown as AcSpecification.NotRule).rule));
            case 'string_comparison':
            case 'comparison':
                return this.createComparison(rule as AcSpecification.ComparisonRule)
        }

        throw Error;
    }

    private static createComparison(rule: AcSpecification.ComparisonRule): Specification {
        return new Spec.Comparison(rule.fact, rule.operator);
    }

    private static createAggregate(aggregateRule: AcSpecification.AggregateRule): Specification {
        let specifications: Specification[] = [];

        aggregateRule.rules.forEach(rule => specifications.push(this.map(rule)));

        switch (aggregateRule.specification) {
            case 'or':
                return new Spec.Or(specifications);
            case 'and':
                return new Spec.And(specifications);
        }

        throw Error;
    }

}