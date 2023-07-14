<?php

namespace AC\Admin;

class Menu
{

    /**
     * @var Menu\Item[]
     */
    private $items;

    public function __construct(array $items = [])
    {
        array_map([$this, 'add_item'], $items);
    }

    public function add_item(Menu\Item $item): Menu
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

    public function get_item_by_slug(string $slug): ?Menu\Item
    {
        return $this->items[$slug] ?? null;
    }

}