<?php

declare(strict_types=1);

namespace AC\Setting\Component\Input;

use AC\Setting\Component\AttributeCollection;
use AC\Setting\Component\Input;
use AC\Setting\Component\OptionCollection;

class Option extends Input
{

    protected $options;

    protected $multiple;

    public function __construct(
        string $name,
        string $type,
        OptionCollection $options,
        $default = null,
        $placeholder = null,
        bool $multiple = null,
        AttributeCollection $attributes = null
    ) {
        parent::__construct($name, $type, $default, $placeholder, $attributes);

        $this->options = $options;
        $this->multiple = true === $multiple;
    }

    public function get_options(): OptionCollection
    {
        return $this->options;
    }

    public function is_multiple(): bool
    {
        return $this->multiple;
    }

}