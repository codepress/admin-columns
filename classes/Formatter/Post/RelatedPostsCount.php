<?php

declare(strict_types=1);

namespace AC\Formatter\Post;

use AC\CollectionFormatter;
use AC\Exception\ValueNotFoundException;
use AC\Type\Value;
use AC\Type\ValueCollection;
use WP_Post_Type;

/**
 * Renders the number of posts that reference the row's post through a meta key.
 * The count links to the post table of the referencing post type, pre-filtered on the meta relationship.
 */
class RelatedPostsCount implements CollectionFormatter
{
    private string $meta_key;

    private string $post_type;

    public function __construct(string $meta_key, string $post_type)
    {
        $this->meta_key = $meta_key;
        $this->post_type = $post_type;
    }

    public function format(ValueCollection $collection): Value
    {
        $count = count($collection);

        if ($count < 1) {
            throw new ValueNotFoundException('Collection can not be empty. id: ' . $collection->get_id());
        }

        $post_type = get_post_type_object($this->post_type);

        if (! $post_type) {
            throw ValueNotFoundException::from_id($collection->get_id());
        }

        return new Value(
            $collection->get_id(),
            $this->get_count_label($count, $post_type, (int)$collection->get_id())
        );
    }

    private function get_count_label(int $count, WP_Post_Type $post_type, int $post_id): string
    {
        $label = sprintf(
            '%d %s',
            $count,
            $count > 1
                ? strtolower($post_type->labels->name)
                : strtolower($post_type->labels->singular_name)
        );

        return sprintf(
            '<a href="%s">%s</a>',
            esc_url($this->get_filtered_table_url($post_type->name, $post_id)),
            esc_html($label)
        );
    }

    private function get_filtered_table_url(string $post_type, int $post_id): string
    {
        return add_query_arg(
            [
                'post_type'   => $post_type,
                'ac_related'  => $post_id,
                'ac_meta_key' => $this->meta_key,
            ],
            admin_url('edit.php')
        );
    }

}
