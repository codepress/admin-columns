<?php

namespace AC\Settings\Column;

use AC\Expression\Specification;
use AC\Setting\Component;
use AC\Setting\Formatter;
use AC\Setting\Type\Value;
use AC\Settings;

class LinkLabel extends Settings\Setting implements Formatter
{

    private $link_label;

    public function __construct(string $link_label = null, Specification $specification = null)
    {
        parent::__construct(
            __('Link Label', 'codepress-admin-columns'),
            __('Leave blank to display the URL', 'codepress-admin-columns'),
            Component\Input\OpenFactory::create_text('link_label', $link_label),
            $specification
        );
        $this->link_label = $link_label;
    }

    public function format(Value $value): Value
    {
        $url = (string)$value->get_value();

        if (filter_var($url, FILTER_VALIDATE_URL) && preg_match('/[^\w.-]/', $url)) {
            return $value->with_value(
                ac_helper()->html->link(
                    $url,
                    $this->link_label ?: $url
                )
            );
        }

        return $value;
    }

}