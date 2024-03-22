<?php

declare(strict_types=1);

namespace AC\Setting\Formatter\User;

use AC\Exception\ValueNotFoundException;
use AC\Setting\Formatter;
use AC\Setting\Type\Value;

class FirstPost implements Formatter
{

    private $post_type;

    private $post_stati;

    public function __construct(array $post_type = null, array $post_stati = null)
    {
        $this->post_type = $post_type;
        $this->post_stati = $post_stati;
    }

    public function format(Value $value): Value
    {
        $first_post = $this->get_first_post((int)$value->get_id());

        if ( ! $first_post) {
            throw ValueNotFoundException::from_id($value->get_id());
        }

        return new Value(
            $this->get_first_post((int)$value->get_id())
        );
    }

    private function get_first_post(int $user_id): ?int
    {
        $args = [
            'author'  => $user_id,
            'fields'  => 'ids',
            'number'  => 1,
            'orderby' => 'date',
            'order'   => 'ASC',
        ];

        if ($this->post_stati) {
            $args['post_status'] = $this->post_stati;
        }

        if ($this->post_type) {
            $args['post_type'] = $this->post_type;
        }

        $post_id = get_posts($args)[0] ?? null;

        return $post_id
            ? (int)$post_id
            : null;
    }

}