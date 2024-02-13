<?php

declare(strict_types=1);

namespace AC\Setting\Formatter\Post;

use AC;
use AC\Setting\Formatter;
use AC\Setting\Type\Value;

class UsedByMenu implements Formatter
{

    private $post_type;

    public function __construct(string $post_type)
    {
        $this->post_type = $post_type;
    }

    public function format(Value $value): Value
    {
        $menus = $this->get_menus(
            (int)$value->get_id(),
            [
                'fields' => 'ids',
                'orderby' => 'name',
            ]
        );

        return $value->with_value(
            $menus
        );
    }

    private function get_menus(int $object_id, array $args = []): array
    {
        $helper = new AC\Helper\Menu();

        return $helper->get_terms(
            $helper->get_ids($object_id, $this->post_type),
            $args
        );
    }

}