<?php

declare(strict_types=1);

namespace AC\Expression;

class FloatRangeSpecification extends RangeSpecification implements TypeSpecification
{

    use TypeTrait;

    public function __construct(string $operator, float $a, float $b)
    {
        parent::__construct($operator, $a, $b);

        $this->type = Types::FLOAT;
    }

    public function is_satisfied_by($value): bool
    {
        return parent::is_satisfied_by((float)$value);
    }

    public function export(): array
    {
        return array_merge(
            parent::export(),
            $this->export_type()
        );
    }

}