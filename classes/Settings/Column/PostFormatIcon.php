<?php

namespace AC\Settings\Column;

use AC;
use AC\Setting\ArrayImmutable;
use AC\Setting\Formatter;
use AC\Setting\Type\Value;
use AC\Settings;
use AC\Expression\Specification;

class PostFormatIcon extends Settings\Column implements Formatter
{

    public function __construct(AC\Column $column, Specification $conditions = null)
    {
        $this->name = 'use_icon';
        $this->label = __('Use an icon?', 'codepress-admin-columns');
        $this->description = __('Use an icon instead of text for displaying.', 'codepress-admin-columns');
        $this->input = AC\Setting\Input\Option\Single::create_toggle(
            (new AC\Setting\OptionCollectionFactory\ToggleOptionCollection())->create()
        );

        parent::__construct(
            $column,
            $conditions
        );
    }

    public function format(Value $value, ArrayImmutable $options): Value
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