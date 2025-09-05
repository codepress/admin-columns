<?php

declare(strict_types=1);

namespace AC\Value\Formatter;

use AC\Setting\Formatter;
use AC\Type\Value;

class Linkable implements Formatter
{

    private string $target;

    private ?string $custom_label;

    private bool $strip_protocol;

    public function __construct(?string $custom_label = null, string $target = '_self', bool $strip_protocol = false)
    {
        $this->target = $target;
        $this->custom_label = $custom_label;
        $this->strip_protocol = $strip_protocol;
    }

    public function format(Value $value): Value
    {
        $url = $value->get_value();

        if (filter_var($url, FILTER_VALIDATE_URL) && preg_match('/[^\w.-]/', $url)) {
            $link_label = $this->get_formatted_label($url);

            return $value->with_value(ac_helper()->html->link($url, $link_label, ['target' => $this->target]));
        }

        if (filter_var($url, FILTER_VALIDATE_EMAIL)) {
            $link_label = $this->get_formatted_label($url);
            $mailto_link = 'mailto:' . $url;

            return $value->with_value(ac_helper()->html->link($mailto_link, $link_label, ['target' => $this->target]));
        }

        return $value;
    }

    private function get_formatted_label(string $url): string
    {
        $link_label = $this->custom_label ?: $url;

        if ($this->strip_protocol) {
            $link_label = urldecode(str_replace(['http://', 'https://'], '', $link_label));
        }

        return $link_label;
    }

}