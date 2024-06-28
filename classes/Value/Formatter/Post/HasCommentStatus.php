<?php

declare(strict_types=1);

namespace AC\Value\Formatter\Post;

use AC\Setting\Formatter;
use AC\Type\Value;

class HasCommentStatus implements Formatter
{

    private $status;

    public function __construct(string $status)
    {
        $this->status = $status;
    }

    public function format(Value $value): Value
    {
        $raw_status = get_post_field('comment_status', $value->get_id(), 'raw');

        return $value->with_value(
            ac_helper()->icon->yes_or_no(
                $this->status === $raw_status,
                $raw_status
            )
        );
    }

}