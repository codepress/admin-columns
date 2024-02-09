<?php

declare(strict_types=1);

namespace AC\Setting;

use AC\Setting\Type\Value;

// TODO David maybe have two interfaces, one that is a setting and one that is used for the column?
interface Formatter
{

    public function format(Value $value): Value;

}