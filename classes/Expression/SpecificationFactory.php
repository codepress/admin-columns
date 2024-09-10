<?php

declare(strict_types=1);

namespace AC\Expression;

interface SpecificationFactory
{

    public function create(array $rule): Specification;

}