<?php

declare(strict_types=1);

namespace AC\Service;

use AC\ColumnRepository\Sort\ManualOrder;
use AC\ListScreen;
use AC\Registerable;
use AC\Setting\ContextFactory;
use AC\TableScreen;
use AC\TableScreen\ManageHeadingFactory;

class ManageHeadings implements Registerable
{

    private static array $factories = [];

    private ContextFactory $context_factory;

    public function __construct(ContextFactory $context_factory)
    {
        $this->context_factory = $context_factory;
    }

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

    protected function get_column_headings(ListScreen $list_screen): array
    {
        $headings = [];

        $sort_strategy = new ManualOrder($list_screen->get_id());

        foreach ($sort_strategy->sort($list_screen->get_columns()) as $column) {
            $setting = $column->get_setting('label');

            $label = $setting
                ? (string)$setting->get_input()->get_value()
                : $column->get_label();

            $headings[(string)$column->get_id()] = apply_filters(
                'ac/column/heading/label',
                $label,
                $this->context_factory->create($column, $list_screen->get_table_screen()),
                $list_screen->get_table_screen()
            );
        }

        return $headings;
    }

    public function handle(ListScreen $list_screen, TableScreen $table_screen): void
    {
        $factory = $this->get_factory($table_screen);

        if ( ! $factory) {
            return;
        }

        $headings = $this->get_column_headings($list_screen);

        if ( ! $headings) {
            return;
        }

        $service = $factory->create($table_screen, $headings);

        if ( ! $service) {
            return;
        }

        $service->register();
    }

}