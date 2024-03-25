<?php

declare(strict_types=1);

namespace AC\Setting\Formatter;

use AC\Setting\Formatter;
use AC\Setting\Type\Value;

class Linkable implements Formatter
{

    private $target;

    private $custom_label;

    public function __construct(string $custom_label = null, string $target = '_self')
    {
        $this->target = $target;
        $this->custom_label = $custom_label;
    }

    public function format(Value $value): Value
    {
        $url = $value->get_value();

        if (filter_var($url, FILTER_VALIDATE_URL) && preg_match('/[^\w.-]/', $url)) {
            $link_label = $this->custom_label ?: $url;

            return $value->with_value(ac_helper()->html->link($url, $link_label, ['target' => $this->target]));
        }

        if (filter_var($url, FILTER_VALIDATE_EMAIL)) {
            $link_label = $this->custom_label ?:$url;
            $mailto_link = 'mailto:' . $url;

            return $value->with_value(ac_helper()->html->link($mailto_link, $link_label, ['target' => $this->target]));
        }

        return $value;
    }

}