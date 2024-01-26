<?php

declare(strict_types=1);

namespace AC\Settings\Column;

<<<<<<< HEAD
use AC;
use AC\Setting\SettingTrait;
=======
use AC\Setting\Input;
use AC\Setting\Input\Custom;
>>>>>>> bf39a92dd4a8273b3c8a4ed1eb27b15114e9f4a2
use AC\Settings\Column;

abstract class Single extends Column
{

    public function __construct(string $name, string $label, string $description, Input $input = null)
    {
<<<<<<< HEAD
        $this->name = $name;

        parent::__construct($column);
=======
        if (null === $input) {
            $input = new Custom($name);
        }

        parent::__construct($name, $label, $description, $input);
>>>>>>> bf39a92dd4a8273b3c8a4ed1eb27b15114e9f4a2
    }

}