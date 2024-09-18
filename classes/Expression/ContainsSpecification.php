<?php

declare(strict_types=1);

namespace AC\Expression;

final class ContainsSpecification extends StringComparisonSpecification
{

    public function __construct(string $fact)
    {
        parent::__construct(StringOperators::CONTAINS, $fact);
    }

    public function is_satisfied_by($value): bool
    {
        return $this->fact !== '' && str_contains((string)$value, $this->fact);
    }

}