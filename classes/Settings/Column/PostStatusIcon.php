<?php

namespace AC\Settings\Column;

use AC\Expression\Specification;
use AC\Setting\Control\Input\OptionFactory;
use AC\Setting\Control\OptionCollection;
use AC\Setting\Formatter;
use AC\Setting\Type\Value;
use AC\Settings;

class PostStatusIcon extends Settings\Control implements Formatter
{

    private $use_icon;

    public function __construct(bool $use_icon, Specification $conditionals = null)
    {
        parent::__construct(
            OptionFactory::create_toggle(
                'use_icon',
                OptionCollection::from_array([
                    '1',
                    '0',
                ], false),
                $use_icon ? '1' : '0'
            ),
            __('Use an icon?', 'codepress-admin-columns'),
            __('Use an icon instead of text for displaying the status.', 'codepress-admin-columns'),
            $conditionals
        );
        $this->use_icon = $use_icon;
    }

    public function format(Value $value): Value
    {
        global $wp_post_statuses;

        $post = get_post($value->get_id());

        if ( ! $post) {
            return $value;
        }

        $status = (string)$value;

        // TODO test
        if ($this->use_icon) {
            $html = ac_helper()->post->get_status_icon($post);

            if ($post->post_password) {
                $html .= ac_helper()->html->tooltip(
                    ac_helper()->icon->dashicon(['icon' => 'lock', 'class' => 'gray']),
                    __('Password protected')
                );
            }

            return $value->with_value($html);
        }

        if (isset($wp_post_statuses[$status])) {
            $html = $wp_post_statuses[$status]->label;

            if ('future' === $status) {
                $html = sprintf(
                    "%s <p class='description'>%s</p>",
                    $html,
                    ac_helper()->date->date($post->post_date, 'wp_date_time')
                );
            }

            return $value->with_value($html);
        }

        return $value;
    }

}