<?php

declare(strict_types=1);

namespace AC\Admin\MenuGroupFactory;

use AC\Admin\MenuGroupFactory;
use AC\Admin\Type\MenuGroup;
use AC\TableScreen;

class Aggregate implements MenuGroupFactory
{

    /**
     * @var array{factory: MenuGroupFactory, priority: int}[]
     */
    private static array $factories = [];

    public static function add(MenuGroupFactory $factory, int $priority = 10): void
    {
        self::$factories[] = [
            'factory'  => $factory,
            'priority' => $priority,
        ];

        usort(self::$factories, static function (array $a, array $b): int {
            return $a['priority'] - $b['priority'];
        });
    }

    public function create(TableScreen $table_screen): MenuGroup
    {
        foreach (self::$factories as $entry) {
            $group = $entry['factory']->create($table_screen);

            if ($group) {
                return $group;
            }
        }

        return new MenuGroup('other', __('Other', 'codepress-admin-columns'));
    }

}