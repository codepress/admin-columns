<?php

declare(strict_types=1);

namespace AC\Setting\Formatter\Comment;

use AC\Setting\Formatter;
use AC\Setting\Type\Value;

class StatusLabel implements Formatter
{

    public function format(Value $value): Value
    {
        $comment = get_comment($value->get_id());

        if ( ! $comment) {
            return $value->with_value(false);
        }

        $status = $comment->comment_approved;
        $statuses = $this->get_statuses();

        $label = array_key_exists($status, $statuses)
            ? $statuses[$status]
            : $status;

        return $value->with_value($label);
    }

    private function get_statuses()
    {
        return [
            'trash'        => __('Trash'),
            'post-trashed' => __('Trash'),
            'spam'         => __('Spam'),
            '1'            => __('Approved'),
            '0'            => __('Pending'),
        ];
    }

}