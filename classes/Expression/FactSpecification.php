<?php

declare(strict_types=1);

namespace AC\Expression;

abstract class FactSpecification implements Specification
{

    use TypeTrait;

    protected $fact;

    public function __construct($fact)
    {
        $this->fact = $fact;
    }

    public function get_rules(string $value): array
    {
        return [
            Rules::TYPE  => $this->get_type(),
            Rules::VALUE => $value,
            Rules::FACT  => $this->fact,
        ];
    }

}