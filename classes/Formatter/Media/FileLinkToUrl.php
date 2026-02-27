<?php

declare(strict_types=1);

namespace AC\Formatter\Media;

use AC\Formatter;
use AC\Helper;
use AC\Type\Value;

class FileLinkToUrl implements Formatter
{

    public function format(Value $value): Value
    {
        $url = wp_get_attachment_url($value->get_id());

        if ( ! $url) {
            return $value;
        }

        return $value->with_value(
            Helper\Html::create()->link(
                $url,
                (string)$value
            )
        );
    }

}