<?php

declare(strict_types=1);

namespace AC\Value\Formatter;

use AC\Helper\Menu;
use AC\Setting\Formatter;
use AC\Type\Value;
use AC\Type\ValueCollection;

class UsedByMenu implements Formatter
{

    private $item_type;

    public function __construct(string $item_type)
    {
        $this->item_type = $item_type;
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
            $helper->get_ids($object_id, $this->item_type),
            [
                'orderby' => 'name',
            ]
        );
    }

}