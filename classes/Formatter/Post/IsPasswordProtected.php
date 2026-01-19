<?php

declare(strict_types=1);

namespace AC\Formatter\Post;

use AC\Formatter;
use AC\Type\Value;

class IsPasswordProtected implements Formatter
{

    public function format(Value $value): Value
    {
        $password = get_post_field('post_password', $value->get_id(), 'raw');

        if ( ! $password) {
            return new Value(null);
        }

        $tooltip = sprintf(
            '<strong>%s</strong>: %s',
            __('Password', 'codepress-admin-columns'),
            $password
        );

        return $value->with_value(
            ac_helper()->icon->yes($tooltip)
        );
    }

}