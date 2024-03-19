<?php

declare(strict_types=1);

namespace AC\Setting\Formatter\Post;

use AC\Setting\Formatter;
use AC\Setting\Type\Value;
use AC\Setting\ValueCollection;

class Attachments implements Formatter
{

    public function format(Value $value): ValueCollection
    {
        $attachment_ids = get_posts([
            'post_type'      => 'attachment',
            'posts_per_page' => -1,
            'post_status'    => null,
            'post_parent'    => $value->get_value(),
            'fields'         => 'ids',
        ]);

        return ValueCollection::from_ids($attachment_ids);
    }

}