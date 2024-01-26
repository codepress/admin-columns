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
        string $type,
        OptionCollection $options,
        $default = null,
        $placeholder = null,
        bool $multiple = null,
        AttributeCollection $attributes = null
    ) {
        if (null === $multiple) {
            $this->multiple = false;
        }

        parent::__construct($type, $default, $placeholder, $attributes);

        $this->options = $options;
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