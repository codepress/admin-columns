<?php

declare(strict_types=1);

namespace AC\Expression;

abstract class AggregateSpecification extends Specification
{

    public const RULES = 'rules';

    /**
     * @var Specification[]
     */
    protected array $specifications;

    public function __construct(array $specifications)
    {
        array_map([$this, 'add'], $specifications);
    }

    private function add(Specification $specification): void
    {
        $this->specifications[] = $specification;
    }

    public function export(): array
    {
        $rules = [];

        foreach ($this->specifications as $specification) {
            $rules[] = $specification->export();
        }

        return array_merge([
            self::RULES => $rules,
        ], parent::export());
    }

}