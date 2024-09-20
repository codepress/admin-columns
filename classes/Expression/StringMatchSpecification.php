<?php

declare(strict_types=1);

namespace AC\Expression;

abstract class StringMatchSpecification extends Specification implements FactSpecification
{

    protected string $fact;

    public function __construct(string $fact)
    {
        $this->fact = $fact;
    }

    public function export(): array
    {
        return array_merge([
            self::FACT => $this->fact,
        ], parent::export());
    }

}