<?php

declare(strict_types=1);

namespace AC\Setting\Input\Option;

use AC\Setting\Input;
use AC\Setting\OptionCollection;

final class Multiple extends Input\Option
{

    public function __construct(
        string $type,
        OptionCollection $options,
        array $default = null,
        string $placeholder = null,
        string $class = null
    ) {
        parent::__construct($type, $options, $default, $placeholder, $class);
    }

    public static function create_select(
        OptionCollection $options,
        array $default = null,
        string $placeholder = null,
        string $class = null
    ): self {
        return new self('select', $options, $default, $placeholder, $class);
    }

}