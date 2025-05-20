<?php

namespace AC\Admin\Section\Partial;

use AC\Admin\MenuListFactory;
use AC\ListScreenGroupsFactory;
use AC\View;

class Menu
{

    private $menu_factory;

    public function __construct(MenuListFactory $menu_factory)
    {
        $this->menu_factory = $menu_factory;
    }

    public function render(string $current, string $url, string $label, bool $is_hidden = false): string
    {
        $menu = new View([
            'items'       => $this->get_menu_items(),
            'current'     => $current,
            'screen_link' => $url,
            'label'       => $label,
            'class'       => $is_hidden ? 'hidden' : '',
        ]);

        return $menu->set_template('admin/edit-menu')
                    ->render();
    }

    private function get_menu_items(): array
    {
        $items = [];

        foreach ($this->menu_factory->create()->all() as $item) {
            $items[$item->get_group()][$item->get_key()] = $item->get_label();
        }

        $grouped = [];

        foreach (ListScreenGroupsFactory::create()->get_all() as $group) {
            $slug = $group['slug'];

            if (empty($items[$slug])) {
                continue;
            }

            if ( ! isset($grouped[$slug])) {
                $grouped[$slug]['title'] = $group['label'];
            }

            natcasesort($items[$slug]);

            $grouped[$slug]['options'] = $items[$slug];

            unset($items[$slug]);
        }

        return $grouped;
    }

}