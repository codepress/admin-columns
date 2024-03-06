<?php

namespace AC\Settings\Column;

use AC\Expression\Specification;
use AC\Setting\Control\Input\OptionFactory;
use AC\Setting\Formatter;
use AC\Setting\Type\Value;
use AC\Settings;

class PostFormatIcon extends Settings\Control implements Formatter
{

    private $use_icon;

    public function __construct(bool $use_icon, Specification $conditions = null)
    {
        parent::__construct(
            OptionFactory::create_toggle('use_icon', null, $use_icon ? 'on' : 'off'),
            __('Use an icon?', 'codepress-admin-columns'),
            __('Use an icon instead of text for displaying.', 'codepress-admin-columns'),
            $conditions
        );
        $this->use_icon = $use_icon;
    }

    public function format(Value $value): Value
    {
        if ($this->use_icon) {
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