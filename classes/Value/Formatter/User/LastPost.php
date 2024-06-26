<?php

declare(strict_types=1);

namespace AC\Value\Formatter\User;

use AC\Exception\ValueNotFoundException;
use AC\Setting\Formatter;
use AC\Type\Value;

class LastPost implements Formatter
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
        $post_id = $this->get_last_post((int)$value->get_id());

        if ( ! $post_id) {
            throw ValueNotFoundException::from_id($value->get_id());
        }

        return new Value($post_id);
    }

    private function get_last_post(int $user_id): ?int
    {
        $args = [
            'author' => $user_id,
            'fields' => 'ids',
            'number' => 1,
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