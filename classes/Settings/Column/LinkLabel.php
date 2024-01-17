<?php

namespace AC\Settings\Column;

use AC\Expression\Specification;
use AC\Setting\ArrayImmutable;
use AC\Setting\Formatter;
use AC\Setting\Input;
use AC\Setting\Type\Value;
use AC\Settings;

// TODO formatter
class LinkLabel extends Settings\Column implements Formatter
{

    public function __construct(Specification $specification)
    {
        parent::__construct(
            'link_label',
            __('Link Label', 'codepress-admin-columns'),
            __('Leave blank to display the URL', 'codepress-admin-columns'),
            Input\Open::create_text(),
            $specification
        );
    }

    public function format(Value $value, ArrayImmutable $options): Value
    {
        $url = $value->get_value();

        if (filter_var($url, FILTER_VALIDATE_URL) && preg_match('/[^\w.-]/', $url)) {
            $label = $options->get('link_label');

            if ( ! $label) {
                $label = $url;
            }

            return $value->with_value(ac_helper()->html->link($url, $label));
        }

        return $value;
    }

}