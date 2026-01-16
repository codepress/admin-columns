<?php

declare(strict_types=1);

namespace AC\Formatter;

use AC\Formatter;
use AC\Type\Value;

class BooleanLabel implements Formatter
{

    private string $true_label;

    private string $false_label;

    public function __construct(string $true_label = 'Yes', string $false_label = 'No')
    {
        $this->true_label = $true_label;
        $this->false_label = $false_label;
    }

    public function format(Value $value)
    {
        return $value->with_value(
            $value->get_value()
                ? $this->true_label
                : $this->false_label
        );
    }

}