<?php

declare(strict_types=1);

namespace AC\Setting\Input;

use AC\Setting\Input;
use AC\Setting\OptionCollection;

interface Option extends Input
{

    public function get_options(): OptionCollection;

}