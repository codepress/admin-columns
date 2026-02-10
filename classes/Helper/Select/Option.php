<?php

namespace AC\Helper\Select;

class Option
{

    private string $value;

    private string $label;

    public function __construct(string $value, ?string $label = null)
    {
        $this->value = $value;
        $this->label = $label ?? $value;
    }

    public function get_value(): string
    {
        return $this->value;
    }

    public function get_label(): string
    {
        return $this->label;
    }

}