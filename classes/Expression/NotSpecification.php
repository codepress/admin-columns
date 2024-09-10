<?php

declare(strict_types=1);

namespace AC\Expression;

final class NotSpecification implements Specification
{

    use SpecificationTrait;

    private Specification $specification;

    public function __construct(Specification $specification)
    {
        $this->specification = $specification;
    }

    public function is_satisfied_by(string $value): bool
    {
        return ! $this->specification->is_satisfied_by($value);
    }

    public function get_rules(): array
    {
        return [
            Rules::OPERATOR => LogicalOperators::LOGICAL_NOT,
            'rule'          => $this->specification->get_rules(),
        ];
    }

}