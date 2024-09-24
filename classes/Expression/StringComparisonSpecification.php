<?php

declare(strict_types=1);

namespace AC\Expression;

class StringComparisonSpecification extends ComparisonSpecification implements TypeSpecification
{

    use TypeTrait;

    public function __construct(string $fact, string $operator)
    {
        parent::__construct($fact, $operator);

        $this->type = Types::STRING;
    }

    public static function equal(string $fact): self
    {
        return new self(ComparisonOperators::EQUAL, $fact);
    }

    public function is_satisfied_by($value): bool
    {
        return parent::is_satisfied_by((string)$value);
    }

    public function export(): array
    {
        return array_merge(
            parent::export(),
            $this->export_type()
        );
    }

}