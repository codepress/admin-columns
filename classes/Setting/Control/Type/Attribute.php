<?php

declare(strict_types=1);

namespace AC\Setting\Control\Type;

final class Attribute
{

    private $name;

    private $value;

    public function __construct(string $name, string $value)
    {
        $this->name = $name;
        $this->value = $value;
    }

    public function get_name(): string
    {
        return $this->name;
    }

    public function get_value(): string
    {
        return $this->value;
    }

}