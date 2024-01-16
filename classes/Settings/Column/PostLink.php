<?php

namespace AC\Settings\Column;

use AC;
use AC\Setting\Input;
use AC\Setting\OptionCollection;
use AC\Setting\Type\Value;
use AC\Settings;
use AC\Expression\Specification;

class PostLink extends Settings\Column implements AC\Setting\Formatter
{

    use AC\Setting\SettingTrait;

    public function __construct(AC\Column $column, Specification $conditions = null)
    {
        $this->name = 'post_link_to';
        $this->label = __('Link To', 'codepress-admin-columns');
        $this->input = Input\Option\Single::create_select(
            OptionCollection::from_array($this->get_display_options()),
            'edit_post'
        );

        parent::__construct($column, $conditions);
    }

    public function format(Value $value, AC\Setting\ArrayImmutable $options): Value
    {
        switch ((string)$options->get($this->name)) {
            case 'edit_post':
                $link = get_edit_post_link($value->get_id());

                break;
            case 'view_post' :
                $link = get_permalink($value->get_id());

                break;
            case 'edit_author':
                $link = get_edit_user_link(ac_helper()->post->get_raw_field('post_author', $value->get_value()));

                break;
            case 'view_author':
                $link = get_author_posts_url(ac_helper()->post->get_raw_field('post_author', $value->get_value()));

                break;

            default:
                return $value;
        }

        return $link
            ? $value->with_value(ac_helper()->html->link($link, $value))
            : $value;
    }

    protected function get_display_options()
    {
        // Default options
        $options = [
            ''            => __('None'),
            'edit_post'   => __('Edit Post'),
            'view_post'   => __('View Post'),
            'edit_author' => __('Edit Post Author', 'codepress-admin-columns'),
            'view_author' => __('View Public Post Author Page', 'codepress-admin-columns'),
        ];

        if ($this->column instanceof AC\Column\Relation) {
            $relation_options = [
                'edit_post'   => _x('Edit %s', 'post'),
                'view_post'   => _x('View %s', 'post'),
                'edit_author' => _x('Edit %s Author', 'post', 'codepress-admin-columns'),
                'view_author' => _x('View Public %s Author Page', 'post', 'codepress-admin-columns'),
            ];

            $label = $this->column->get_relation_object()->get_labels()->singular_name;

            foreach ($relation_options as $k => $option) {
                $options[$k] = sprintf($option, $label);
            }
        }

        return $options;
    }

}