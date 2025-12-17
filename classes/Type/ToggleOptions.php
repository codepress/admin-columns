<?php

namespace AC\Type;

use AC\Helper\Select\Option;
use InvalidArgumentException;

final class ToggleOptions
{

    private Option $disabled;

    private Option $enabled;

    public function __construct(Option $disabled, Option $enabled)
    {
        $this->disabled = $disabled;
        $this->enabled = $enabled;

        $this->validate();
    }

    private function validate(): void
    {
        if ( (string)$this->disabled->get_value() === (string)$this->enabled->get_value()) {
            throw new InvalidArgumentException('Values for enabled and disabled cannot be the same.');
        }
    }

    public function get_enabled(): Option
    {
        return $this->enabled;
    }

    public function get_disabled(): Option
    {
        return $this->disabled;
    }

    public static function create_from_values(string $disabled_value = '0', string $enabled_value = '1'): self
    {
        return new self(
            new Option($disabled_value),
            new Option($enabled_value),
        );
    }

    public static function create_from_array(array $options): self
    {
        $first_key = array_key_first($options);
        $last_key = array_key_last($options);

        return new self(
            new Option($first_key, $options[$first_key]),
            new Option($last_key, $options[$last_key])
        );
    }

}