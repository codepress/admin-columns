<?php

declare(strict_types=1);

namespace AC\Expression;

class FloatComparisonSpecification extends ComparisonSpecification implements TypeSpecification
{

    use TypeTrait;

    public function __construct(string $operator, string $fact)
    {
        parent::__construct(
            $operator,
            $this->create_float_from_value($fact)
        );

        $this->type = Types::FLOAT;
    }

    public function is_satisfied_by($value): bool
    {
        return '' !== $value && parent::is_satisfied_by((float)$value);
    }

    private function create_float_from_value(string $fact): float
    {
        // convert price to float
        if (str_contains($fact, ',')) {
            $fact = str_replace(',', '.', $fact);
        }

        return (float)$fact;
    }

    public function export(): array
    {
        return array_merge(
            parent::export(),
            $this->export_type()
        );
    }

}