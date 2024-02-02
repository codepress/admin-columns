<?php

declare(strict_types=1);

namespace AC\Settings\Column;

use AC;
use AC\Expression\Specification;
use AC\Setting\Component\Input\OptionFactory;
use AC\Setting\Component\OptionCollection;
use AC\Setting\Type\Value;
use AC\Settings;

class PostLink extends Settings\Column implements AC\Setting\Formatter
{

    private $relation;

    public function __construct(string $post_link_to, AC\Relation $relation = null, Specification $conditions = null)
    {
        parent::__construct(
            __('Link To', 'codepress-admin-columns'),
            '',
            OptionFactory::create_select(
                'post_link_to',
                OptionCollection::from_array($this->get_display_options()),
                $post_link_to ?: 'edit_post'
            ),
            $conditions
        );

        $this->relation = $relation;
    }

    public function format(Value $value, AC\Setting\Config $options): Value
    {
        switch ((string)$options->get($this->name)) {
            case 'edit_post':
                $link = get_edit_post_link($value->get_id());

                break;
            case 'view_post' :
                $link = get_permalink($value->get_id());

                break;
            case 'edit_author':
                $link = get_edit_user_link(ac_helper()->post->get_raw_field('post_author', (int)$value->get_value()));

                break;
            case 'view_author':
                $link = get_author_posts_url(ac_helper()->post->get_raw_field('post_author', (int)$value->get_value()));

                break;

            default:
                return $value;
        }

        return $link
            ? $value->with_value(ac_helper()->html->link($link, $value))
            : $value;
    }

    protected function get_display_options(): array
    {
        // Default options
        $options = [
            ''            => __('None'),
            'edit_post'   => __('Edit Post'),
            'view_post'   => __('View Post'),
            'edit_author' => __('Edit Post Author', 'codepress-admin-columns'),
            'view_author' => __('View Public Post Author Page', 'codepress-admin-columns'),
        ];

        if ($this->relation) {
            $relation_options = [
                'edit_post'   => _x('Edit %s', 'post'),
                'view_post'   => _x('View %s', 'post'),
                'edit_author' => _x('Edit %s Author', 'post', 'codepress-admin-columns'),
                'view_author' => _x('View Public %s Author Page', 'post', 'codepress-admin-columns'),
            ];

            $label = $this->relation->get_labels()->singular_name;

            foreach ($relation_options as $k => $option) {
                $options[$k] = sprintf($option, $label);
            }
        }

        return $options;
    }

}