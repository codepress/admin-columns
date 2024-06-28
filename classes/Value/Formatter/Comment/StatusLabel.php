<?php

declare(strict_types=1);

namespace AC\Value\Formatter\Comment;

use AC\Setting\Formatter;
use AC\Type\Value;

class StatusLabel implements Formatter
{

    public function format(Value $value): Value
    {
        $comment = get_comment($value->get_id());

        if ( ! $comment) {
            return new Value(null);
        }

        $status = $comment->comment_approved;
        $statuses = $this->get_statuses();

        $label = array_key_exists($status, $statuses)
            ? $statuses[$status]
            : $status;

        return $value->with_value($label);
    }

    private function get_statuses(): array
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