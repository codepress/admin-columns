<?php

declare(strict_types=1);

namespace AC\Expression;

class IntegerRangeSpecification extends RangeSpecification implements TypeSpecification
{

    use TypeTrait;

    public function __construct(string $operator, int $a, int $b)
    {
        parent::__construct($operator, $a, $b);

        $this->type = Types::INTEGER;
    }

    public function is_satisfied_by($value): bool
    {
        return parent::is_satisfied_by((int)$value);
    }

    public function export(): array
    {
        return array_merge(
            parent::export(),
            $this->export_type()
        );
    }

}