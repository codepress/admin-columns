<?php

declare(strict_types=1);

namespace AC\Expression;

final class EndsWithSpecification extends FactSpecification
{

    use SpecificationTrait;

    public function is_satisfied_by(string $value): bool
    {
        return $this->fact !== '' && str_ends_with($value, $this->fact);
    }

    protected function get_type(): string
    {
        return 'ends_with';
    }

}