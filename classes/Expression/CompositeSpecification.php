<?php

declare(strict_types=1);

namespace AC\Expression;

abstract class CompositeSpecification extends Specification
{

    public const RULE = 'rule';

    protected Specification $specification;

    public function __construct(Specification $specification)
    {
        $this->specification = $specification;
    }

    public function export(): array
    {
        return array_merge([
            self::RULE => $this->specification->export(),
        ], parent::export());
    }

}