<?php

namespace AC\Admin;

use AC\Admin\Type\MenuItem;

class Menu
{

    /**
     * @var MenuItem[]
     */
    private $items;

    public function __construct(array $items = [])
    {
        array_map([$this, 'add_item'], $items);
    }

    public function add_item(Type\MenuItem $item): Menu
    {
        $this->items[$item->get_slug()] = $item;

        return $this;
    }

    public function remove_item(string $slug): Menu
    {
        unset($this->items[$slug]);

        return $this;
    }

    public function get_items(): array
    {
        return $this->items;
    }

    public function get_item_by_slug(string $slug): ?Type\MenuItem
    {
        return $this->items[$slug] ?? null;
    }

}