<?php

declare(strict_types=1);

namespace AC\Setting\Formatter\Post;

use AC\Helper\Menu;
use AC\Setting\Formatter;
use AC\Setting\Type\Value;
use AC\Setting\ValueCollection;

class UsedByMenu implements Formatter
{

    private $post_type;

    public function __construct(string $post_type)
    {
        $this->post_type = $post_type;
    }

    public function format(Value $value)
    {
        $collection = new ValueCollection($value->get_id());

        foreach ($this->get_menu_terms((int)$value->get_id()) as $term) {
            $collection->add(new Value($term->term_id, $term->name));
        }

        return $collection;
    }

    private function get_menu_terms(int $object_id): array
    {
        $helper = new Menu();

        return $helper->get_terms(
            $helper->get_ids($object_id, $this->post_type),
            [
                'orderby' => 'name',
            ]
        );
    }

}