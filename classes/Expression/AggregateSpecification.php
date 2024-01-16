<?php

declare(strict_types=1);

namespace AC\Expression;

abstract class AggregateSpecification implements Specification
{

    use SpecificationTrait;
    use TypeTrait;

    /**
     * @var Specification[]
     */
    protected $specifications;

    public function __construct(array $specifications)
    {
        array_map([$this, 'add'], $specifications);
    }

    private function add(Specification $specification): void
    {
        $this->specifications[] = $specification;
    }

    public function get_rules(string $value): array
    {
        $rules = [];

        foreach ($this->specifications as $specification) {
            $rules[] = $specification->get_rules($value);
        }

        return [
            Rules::TYPE  => $this->get_type(),
            Rules::RULES => $rules,
        ];
    }

}