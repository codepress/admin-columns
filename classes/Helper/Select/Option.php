<?php

declare(strict_types=1);

namespace AC\Helper\Select;

final class Option
{

    private $value;

    private string $label;

    // TODO Stefan check if value if always string!
    public function __construct($value, ?string $label = null)
    {
        $this->value = $value;
        $this->label = $label ?: (string)$value;
    }

    public function get_value()
    {
        return $this->value;
    }

    public function get_label(): string
    {
        return $this->label;
    }

}