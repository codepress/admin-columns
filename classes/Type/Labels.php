<?php

declare(strict_types=1);

namespace AC\Type;

class Labels
{

    private $singular;

    private $plural;

    public function __construct(string $singular, string $plural)
    {
        $this->singular = $singular;
        $this->plural = $plural;
    }

    public function get_singular(): string
    {
        return $this->singular;
    }

    public function get_plural(): string
    {
        return $this->plural;
    }

    public function __toString(): string
    {
        return $this->plural;
    }

}