<?php

declare(strict_types=1);

namespace AC\Expression;

final class EndsWithSpecification extends StringMatchSpecification
{

    public function is_satisfied_by($value): bool
    {
        return $this->fact !== '' && str_ends_with((string)$value, $this->fact);
    }

}