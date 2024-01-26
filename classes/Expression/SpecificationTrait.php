<?php

declare(strict_types=1);

namespace AC\Expression;

trait SpecificationTrait
{

    public function and_specification(Specification $specification): Specification
    {
        return new AndSpecification([$this, $specification]);
    }

    public function or_specification(Specification $specification): Specification
    {
        return new OrSpecification([$this, $specification]);
    }

    public function not(): Specification
    {
        return new NotSpecification($this);
    }

}