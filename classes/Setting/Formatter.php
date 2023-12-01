<?php

declare(strict_types=1);

namespace AC\Setting;

use AC;

interface Formatter
{

    /**
     * @param mixed $value
     */
    public function has_formatter($value): bool;

    /**
     * @param mixed $value
     */
    public function get_formatter($value): AC\Formatter;

}