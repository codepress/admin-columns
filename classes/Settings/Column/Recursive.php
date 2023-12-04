<?php

declare(strict_types=1);

namespace AC\Settings\Column;

use AC;
use AC\Settings\Column;

abstract class Recursive extends Column implements AC\Setting\Recursive, AC\Setting\Formatter
{

    use AC\Setting\RecursiveFormatterTrait;

    public function is_parent(): bool
    {
        return false;
    }

}