<?php

declare(strict_types=1);

namespace AC\Expression;

final class EndsWithSpecification extends StringComparisonSpecification
{

    public function __construct(string $fact)
    {
        parent::__construct(StringOperators::ENDS_WITH, $fact);
    }

    public function is_satisfied_by($value): bool
    {
        return $this->fact !== '' && str_ends_with((string)$value, $this->fact);
    }

}