<?php

declare(strict_types=1);

namespace AC\Setting\Type;

final class SettingValue
{

    protected $name;

    private $value;

    public function __construct(string $name, $value)
    {
        $this->name = $name;
        $this->value = $value;
    }

    public function get_name(): string
    {
        return $this->name;
    }

    public function get_value()
    {
        return $this->value;
    }

}