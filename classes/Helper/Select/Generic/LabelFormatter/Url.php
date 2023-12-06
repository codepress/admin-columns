<?php

namespace AC\Helper\Select\Generic\LabelFormatter;

use AC\Helper\Select\Generic\LabelFormatter;

class Url implements LabelFormatter
{

    public function format_label(string $value): string
    {
        return (string)parse_url($value, PHP_URL_HOST);
    }

}