<?php

declare(strict_types=1);

namespace AC\Formatter;

use AC\Formatter;
use AC\Helper;
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
            return $value->with_value($this->create_link($url, $url));
        }

        if (filter_var($url, FILTER_VALIDATE_EMAIL)) {
            return $value->with_value($this->create_link('mailto:' . $url, $url));
        }

        return $value;
    }

    private function create_link(string $href, string $url): string
    {
        $attributes = ['target' => $this->target];

        if ($this->custom_label) {
            return Helper\Html::create()->link_html($href, $this->custom_label, $attributes);
        }

        return Helper\Html::create()->link($href, $this->get_url_label($url), $attributes);
    }

    private function get_url_label(string $url): string
    {
        return $this->strip_protocol
            ? urldecode(str_replace(['http://', 'https://'], '', $url))
            : $url;
    }

}
