<?php

declare(strict_types=1);

namespace AC\Expression;

final class NullSpecification extends Specification
{

    public function __construct()
    {
        parent::__construct('null');
    }

    public function is_satisfied_by($value): bool
    {
        return true;
    }

}