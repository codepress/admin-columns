<?php

declare(strict_types=1);

namespace AC\Settings\Column;

use AC;
use AC\Expression\Specification;
use AC\Setting\Control\Input\OptionFactory;
use AC\Setting\Control\OptionCollection;
use AC\Setting\Type\Value;
use AC\Settings;
use WP_Post;

class PostLink extends Settings\Control implements AC\Setting\Formatter
{

    private $relation;

    protected $post_link_to;

    public function __construct(string $post_link_to, AC\Relation $relation = null, Specification $conditions = null)
    {
        parent::__construct(
            OptionFactory::create_select(
                'post_link_to',
                OptionCollection::from_array($this->get_display_options()),
                $post_link_to
            ),
            __('Link To', 'codepress-admin-columns'),
            null,
            $conditions
        );

        $this->relation = $relation;
        $this->post_link_to = $post_link_to;
    }

    public function format(Value $value): Value
    {
        $post = get_post(
            $value->get_id()
        );

        if ( ! $post instanceof WP_Post) {
            return $value;
        }

        switch ($this->post_link_to) {
            case 'edit_post':
                $link = get_edit_post_link($post);

                break;
            case 'view_post' :
                $link = get_permalink($post);

                break;
            case 'edit_author':
                $link = get_edit_user_link($post->post_author);

                break;
            case 'view_author':
                $link = get_author_posts_url($post->post_author);

                break;
            default:
                $link = null;
        }

        return $link
            ? $value->with_value(ac_helper()->html->link($link, (string)$value))
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