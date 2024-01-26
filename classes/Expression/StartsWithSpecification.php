<?php

declare(strict_types=1);

namespace AC\Expression;

final class StartsWithSpecification extends FactSpecification
{

    use SpecificationTrait;

    public function is_satisfied_by(string $value): bool
    {
        return $this->fact !== '' && str_starts_with($value, $this->fact);
    }

    protected function get_type(): string
    {
        return 'starts_with';
    }

}