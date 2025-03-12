<?php

declare(strict_types=1);

namespace AC\Value\Formatter\Post;

use AC\Exception\ValueNotFoundException;
use AC\Setting\Formatter;
use AC\Type\PostTypeSlug;
use AC\Type\Value;
use AC\Type\ValueCollection;

final class PostReferences implements Formatter
{

    private string $meta_key;

    private ?PostTypeSlug $post_type;

    public function __construct(string $meta_key, ?PostTypeSlug $post_type = null)
    {
        $this->meta_key = $meta_key;
        $this->post_type = $post_type;
    }

    public function format(Value $value): ValueCollection
    {
        $referenced_ids = $this->get_referenced_post_ids((int)$value->get_id());

        if (empty ($referenced_ids)) {
            throw ValueNotFoundException::from_id($value->get_id());
        }

        return ValueCollection::from_ids($value->get_id(), $referenced_ids);
    }

    private function get_referenced_post_ids(int $current_id): array
    {
        return get_posts([
            'post_type'      => $this->post_type ? (string)$this->post_type : 'any',
            'fields'         => 'ids',
            'posts_per_page' => -1,
            'meta_query'     => [
                [
                    'key'   => $this->meta_key,
                    'value' => (int)$current_id,
                ],
            ],
        ]);
    }

}