<?php

declare(strict_types=1);

namespace AC\Expression;

abstract class StringMatchSpecification extends Specification implements FactSpecification
{

    use FactTrait;

    public function __construct(string $fact)
    {
        $this->fact = $fact;
    }

    public function export(): array
    {
        return array_merge(
            parent::export(),
            $this->export_fact()
        );
    }

}