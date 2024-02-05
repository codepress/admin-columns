<?php

declare(strict_types=1);

namespace AC\Column\Post\Renderable;

// TODO turn into formatter
class Attachment extends Formatted
{

    public function get_pre_formatted_value($id): array
    {
        $attachment_ids = get_posts([
            'post_type'      => 'attachment',
            'posts_per_page' => -1,
            'post_status'    => null,
            'post_parent'    => $id,
            'fields'         => 'ids',
        ]);

        if ( ! $attachment_ids) {
            return [];
        }

        return $attachment_ids;
    }

}