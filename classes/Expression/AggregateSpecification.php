<?php

declare(strict_types=1);

namespace AC\Expression;

abstract class AggregateSpecification implements Specification
{

    use SpecificationTrait;
    use OperatorTrait;

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

    public function get_rules(): array
    {
        $rules = [];

        foreach ($this->specifications as $specification) {
            $rules[] = $specification->get_rules();
        }

        return [
            Rules::OPERATOR => $this->get_operator(),
            'rules'         => $rules,
        ];
    }

}