<?php

declare(strict_types=1);

namespace AC\Expression;

abstract class FactSpecification implements Specification
{

    use OperatorTrait;

    /**
     * @var mixed
     */
    protected $fact;

    public function __construct($fact)
    {
        $this->fact = $fact;
    }

    public function get_rules(): array
    {
        return [
            Rules::OPERATOR => $this->get_operator(),
            Rules::FACT     => $this->fact,
        ];
    }

}