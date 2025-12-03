<?php

declare(strict_types=1);

namespace AC\Value\Formatter\Post;

use AC\Exception\ValueNotFoundException;
use AC\Setting\Formatter;
use AC\Type\Value;
use AC\Type\ValueCollection;

class Attachments implements Formatter
{

    public function format(Value $value): ValueCollection
    {
        $parent_id = (int)$value->get_value();

        if ( ! $parent_id) {
            throw new ValueNotFoundException('Parent ID is required');
        }

        $attachment_ids = get_posts([
            'post_type'      => 'attachment',
            'posts_per_page' => -1,
            'post_status'    => null,
            'post_parent'    => $parent_id,
            'fields'         => 'ids',
        ]);

        return ValueCollection::from_ids($parent_id, $attachment_ids);
    }

}