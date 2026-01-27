<?php

namespace AC\Type;

use AC\Helper\Select\Option;

final class ToggleOptions
{

    private Option $disabled;

    private Option $enabled;

    public function __construct(Option $disabled, Option $enabled)
    {
        $this->disabled = $disabled;
        $this->enabled = $enabled;
    }

    public function get_enabled(): Option
    {
        return $this->enabled;
    }

    public function get_disabled(): Option
    {
        return $this->disabled;
    }

    public static function create_from_array(array $options): self
    {
        $first_key = array_key_first($options);
        $last_key = array_key_last($options);

        return new self(
            new Option((string)$first_key, (string)$options[$first_key]),
            new Option((string)$last_key, (string)$options[$last_key])
        );
    }

}