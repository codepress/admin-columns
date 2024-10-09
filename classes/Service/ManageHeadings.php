<?php

declare(strict_types=1);

namespace AC\Service;

use AC\ListScreen;
use AC\Registerable;
use AC\TableScreen;
use AC\TableScreen\ManageHeadingFactory;

class ManageHeadings implements Registerable
{

    private static array $factories = [];

    public static function add(ManageHeadingFactory $factory): void
    {
        self::$factories[] = $factory;
    }

    public function register(): void
    {
        add_action('ac/table/list_screen', [$this, 'handle'], 10, 2);
    }

    private function get_factory(TableScreen $table_screen): ?ManageHeadingFactory
    {
        foreach (array_reverse(self::$factories) as $factory) {
            if ($factory->can_create($table_screen)) {
                return $factory;
            }
        }

        return null;
    }

    public function handle(ListScreen $list_screen, TableScreen $table_screen): void
    {
        $factory = $this->get_factory($table_screen);

        if ($factory) {
            $factory->create($table_screen, $list_screen)
                    ->register();
        }
    }

}