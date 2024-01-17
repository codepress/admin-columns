<?php

declare(strict_types=1);

namespace AC\Settings\Column;

use AC;
use AC\Setting\Option;
use AC\Setting\OptionCollection;
use AC\Settings\Column;

// TODO used?
abstract class Multiple extends Column implements Option
{

    // TODO
    //    use SettingTrait;
    use AC\Setting\OptionTrait;

    public function __construct(AC\Column $column, string $name, OptionCollection $options)
    {
        if (null === $this->input) {
            $this->input = AC\Setting\Input\Option\Multiple::create_select();
        }

        $this->options = $options;
        $this->name = $name;

        parent::__construct($column);
    }

}