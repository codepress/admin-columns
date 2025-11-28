<?php

declare(strict_types=1);

namespace AC\Service;

use AC\ListScreen;
use AC\Registerable;
use AC\Table\ManageValueServiceFactory;
use AC\TableScreen;

class ManageValue implements Registerable
{

    private static array $factories = [];

    public static function add(ManageValueServiceFactory $factory): void
    {
        self::$factories[] = $factory;
    }

    public function register(): void
    {
        add_action('ac/table/list_screen', [$this, 'handle'], 10, 2);
    }

    private function get_service(TableScreen $table_screen, ListScreen $list_screen): ?Registerable
    {
        echo '<pre>';
        print_r(array_reverse(self::$factories));
        echo '</pre>';
        exit;
        foreach (array_reverse(self::$factories) as $factory) {
            $service = $factory->create($table_screen, $list_screen);

            if ($service) {
                return $service;
            }
        }

        return null;
    }

    public function handle(ListScreen $list_screen, TableScreen $table_screen): void
    {
        $service = $this->get_service($table_screen, $list_screen);

        if ($service) {
            $service->register();
        }
    }

}