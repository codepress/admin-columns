<?php

declare(strict_types=1);

namespace AC\Settings\Column;

use AC;
use AC\Setting\Component\OptionCollection;
use AC\Setting\Option;
use AC\Settings\Setting;

// TODO used?
abstract class Multiple extends Setting implements Option
{

    use AC\Setting\OptionTrait;

    public function __construct(AC\Column $column, string $name, OptionCollection $options)
    {
        if (null === $this->input) {
            $this->input = AC\Setting\Component\Input\Option\Multiple::create_select();
        }

        $this->options = $options;
        $this->name = $name;

        parent::__construct($column);
    }

}