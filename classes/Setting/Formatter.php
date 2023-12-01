<?php

declare(strict_types=1);

namespace AC\Setting;

use AC\Setting\Type\Value;

interface Formatter
{

    public function format(Value $value, array $options): Value;

}