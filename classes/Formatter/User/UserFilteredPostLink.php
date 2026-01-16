<?php

declare(strict_types=1);

namespace AC\Formatter\User;

use AC\Formatter;
use AC\Type\Value;

class UserFilteredPostLink implements Formatter
{

    private $post_type;

    public function __construct(string $post_type)
    {
        $this->post_type = $post_type;
    }

    public function format(Value $value): Value
    {
        $link = add_query_arg([
            'post_type' => $this->post_type,
            'author'    => $value->get_id(),
        ], admin_url('edit.php'));

        return $value->with_value(
            sprintf('<a href="%s">%s</a>', $link, $value->get_value())
        );
    }

}