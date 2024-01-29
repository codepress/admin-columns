<?php

namespace AC\Settings\Column;

use AC\Expression\Specification;
use AC\Setting\Config;
use AC\Setting\Formatter;
use AC\Setting\Input;
use AC\Setting\Type\Value;
use AC\Settings;

class PostFormatIcon extends Settings\Column implements Formatter
{

    public function __construct(Specification $conditions = null)
    {
        parent::__construct(
            'use_icon',
            __('Use an icon?', 'codepress-admin-columns'),
            __('Use an icon instead of text for displaying.', 'codepress-admin-columns'),
            Input\Option\Single::create_toggle(),
            $conditions
        );
    }

    public function format(Value $value, Config $options): Value
    {
        if ('on' === $options->get('use_icon')) {
            return $value->get_value()
                ? $value->with_value(
                    ac_helper()->html->tooltip(
                        '<span class="ac-post-state-format post-state-format post-format-icon post-format-' . esc_attr(
                            $value->get_value()
                        ) . '"></span>',
                        get_post_format_string($value->get_value())
                    )
                )
                : $value->with_value(false);
        }

        return $value->get_value()
            ? $value->with_value(esc_html(get_post_format_string($value->get_value())))
            : $value;
    }

}