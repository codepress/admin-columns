<?php

namespace AC\Column;

use AC;
use AC\Column;
use AC\Settings;

/**
 * Column displaying the menus the item is used in. Supported by all object types that
 * can be referenced in menus (i.e. posts).
 */
abstract class Menu extends Column
{

    public function __construct()
    {
        $this->set_type('column-used_by_menu');
        $this->set_label(__('Menu', 'codepress-admin-columns'));
    }

    abstract public function get_object_type(): string;

    abstract public function get_item_type(): string;

    public function get_raw_value($id): array
    {
        return $this->get_menus(
            (int)$id,
            [
                'fields' => 'ids',
                'orderby' => 'name',
            ]
        );
    }

    public function get_menus(int $object_id, array $args = []): array
    {
        $helper = new AC\Helper\Menu();

        return $helper->get_terms(
            $helper->get_ids($object_id, $this->get_object_type()),
            $args
        );
    }

    public function register_settings()
    {
        $this->add_setting(new Settings\Column\LinkToMenu());
    }

}