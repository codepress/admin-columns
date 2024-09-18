<?php

declare(strict_types=1);

namespace AC\Expression;

final class StartsWithSpecification extends StringCOmparisonSpecification
{

    public function __construct(string $fact)
    {
        parent::__construct(StringOperators::STARTS_WITH, $fact);
    }

    public function is_satisfied_by($value): bool
    {
        return $this->fact !== '' && str_starts_with((string)$value, $this->fact);
    }

}