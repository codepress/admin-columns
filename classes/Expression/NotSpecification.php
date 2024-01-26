<?php

declare(strict_types=1);

namespace AC\Expression;

final class NotSpecification implements Specification
{

    use SpecificationTrait;

    private $specification;

    public function __construct(Specification $specification)
    {
        $this->specification = $specification;
    }

    public function is_satisfied_by(string $value): bool
    {
        return ! $this->specification->is_satisfied_by($value);
    }

    public function get_rules(string $value): array
    {
        return [
            Rules::TYPE => 'not',
            Rules::RULE => $this->specification->get_rules($value),
        ];
    }

}