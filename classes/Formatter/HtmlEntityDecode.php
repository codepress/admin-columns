<?php

declare(strict_types=1);

namespace AC\Formatter;

use AC\Formatter;
use AC\Type\Value;

class HtmlEntityDecode implements Formatter
{
    public function format(Value $value): Value
    {
        return $value->with_value(
            html_entity_decode((string)$value, ENT_QUOTES | ENT_HTML5, 'UTF-8')
        );
    }

}
