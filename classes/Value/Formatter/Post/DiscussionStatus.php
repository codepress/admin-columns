<?php

declare(strict_types=1);

namespace AC\Value\Formatter\Post;

use AC\Setting\Formatter;
use AC\Type\Value;

class DiscussionStatus implements Formatter
{

    public function format(Value $value): Value
    {
        $post = get_post($value->get_id());
        $status = $post->comment_status;
        $ping_status = $post->ping_status;

        if ($ping_status === 'open') {
            $status_label = $status === 'open'
                ? __('Open')
                : __('Pings only');
        } else {
            $status_label = $status === 'open'
                ? __('Comments only')
                : __('Closed');
        }

        return $value->with_value($status_label);
    }

}