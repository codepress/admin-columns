<?php

declare(strict_types=1);

namespace AC\Settings\Column;

use AC;
use AC\Setting\Component\OptionCollection;
use AC\Setting\Option;
<<<<<<< HEAD
use AC\Setting\SettingTrait;
=======
use AC\Setting\OptionCollection;
>>>>>>> bf39a92dd4a8273b3c8a4ed1eb27b15114e9f4a2
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
            $this->input = AC\Setting\Component\Input\Option\Multiple::create_select();
        }

        $this->options = $options;
        $this->name = $name;

        parent::__construct($column);
    }

}