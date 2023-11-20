<?php

declare(strict_types=1);

namespace AC\Settings\Column;

use AC;
use AC\Setting\OptionCollection;
use AC\Setting\SettingTrait;
use AC\Settings\Column;

abstract class Recursive extends Column implements AC\Setting\Recursive
{

    use SettingTrait;
    use AC\Setting\RecursiveTrait;

    public function __construct(AC\Column $column, string $name, OptionCollection $options)
    {
        if (null === $this->input) {
            $this->input = AC\Setting\Input\Option\Multiple::create_select($options);
        }

        $this->name = $name;

        parent::__construct($column);
    }

}