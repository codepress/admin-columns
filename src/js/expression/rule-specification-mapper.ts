import Specification from "./";
import SpecificationInterface = AC.Specification.Specification;
import SpecI = AC.Specification;

export default class RuleSpecificationMapper {

    static map(rule: SpecI.Rule): SpecificationInterface {
        switch (rule.specification) {
            case 'null':
                return new Specification.Null();
            case 'or':
            case 'and':
                return this.createAggregate(rule as SpecI.AggregateRule);
            case 'not':
                return new Specification.Not(this.map((rule as unknown as SpecI.NotRule).rule));
            case 'string_comparison':
            case 'comparison':
                return this.createComparison(rule as SpecI.ComparisonRule)
        }

        throw Error;
    }

    private static createComparison(rule: SpecI.ComparisonRule): SpecificationInterface {
        return new Specification.Comparison(rule.fact, rule.operator);
    }

    private static createAggregate(aggregateRule: SpecI.AggregateRule): SpecificationInterface {
        let specifications: SpecificationInterface[] = [];

        aggregateRule.rules.forEach(rule => specifications.push(this.map(rule)));

        switch (aggregateRule.specification) {
            case 'or':
                return new Specification.Or(specifications);
            case 'and':
                return new Specification.And(specifications);
        }

        throw Error;
    }

}