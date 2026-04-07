<?php

declare(strict_types=1);

namespace AC\Formatter;

use AC\Exception\ValueNotFoundException;
use AC\Formatter;
use AC\Type\ToggleOptions;
use AC\Type\Value;

class ToggleValue implements Formatter
{

    private ToggleOptions $options;

    private ?Formatter $formatter;

    public function __construct(ToggleOptions $options, ?Formatter $formatter = null)
    {
        $this->options = $options;
        $this->formatter = $formatter;

        if( ! $formatter ) {
            $this->formatter = new YesNoIcon();
        }
    }

    public function format(Value $value): Value
    {
        $true_value = $value->get_value() === $this->options->get_enabled()->get_value();
        $false_value = $value->get_value() === $this->options->get_disabled()->get_value();

        if( ! $true_value && ! $false_value ) {
            throw ValueNotFoundException::from_id($value->get_id());
        }

        return $this->formatter->format(
            $value->with_value($true_value)
        );
    }

}