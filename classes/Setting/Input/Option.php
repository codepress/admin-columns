<?php

declare(strict_types=1);

namespace AC\Setting\Input;

use AC\Setting\Input;
use AC\Setting\OptionCollection;

abstract class Option extends Input
{

    protected $options;

    public function __construct(
        string $type,
        OptionCollection $options,
        $default = null,
        string $placeholder = null,
        string $class = null
    ) {
        parent::__construct($type, $default, $placeholder, $class);

        $this->options = $options;
    }

    public function get_options(): OptionCollection
    {
        return $this->options;
    }

}