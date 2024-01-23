<?php

declare(strict_types=1);

namespace AC\Settings;

use AC;

class GeneralOptionFactory
{

    public function create(): GeneralOption
    {
        return new GeneralOption(new AC\Storage\Option('cpac_general_options'));
    }

}