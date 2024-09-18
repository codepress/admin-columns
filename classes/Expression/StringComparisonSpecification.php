<?php

declare(strict_types=1);

namespace AC\Expression;

class StringComparisonSpecification extends ComparisonSpecification implements TypeSpecification
{

    public function __construct(string $fact, string $operator)
    {
        parent::__construct($fact, $operator);
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
        return array_merge([
            self::TYPE => Types::STRING,
        ], parent::export());
    }

}