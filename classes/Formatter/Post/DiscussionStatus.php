<?php

declare(strict_types=1);

namespace AC\Formatter\Post;

use AC\Exception\ValueNotFoundException;
use AC\Formatter;
use AC\Type\Value;

class DiscussionStatus implements Formatter
{

    public function format(Value $value): Value
    {
        $post = get_post($value->get_id());

        if ( ! $post) {
            throw ValueNotFoundException::from_id($value->get_id());
        }

        if ($post->ping_status === 'open') {
            $status_label = $post->comment_status === 'open'
                ? __('Open')
                : __('Pings only');
        } else {
            $status_label = $post->comment_status === 'open'
                ? __('Comments only')
                : __('Closed');
        }

        return $value->with_value($status_label);
    }

}