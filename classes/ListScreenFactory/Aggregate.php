<?php

declare(strict_types=1);

namespace AC\ListScreenFactory;

use AC\ColumnFactory;
use AC\Exception\InvalidListScreenException;
use AC\ListScreen;
use AC\ListScreenFactory;
use AC\TableScreen;
use AC\TableScreenFactory;
use AC\Type\ListKey;
use AC\Type\ListScreenId;
use DateTime;

class Aggregate implements ListScreenFactory
{

    protected $table_screen_factory;

    public function __construct(TableScreenFactory $table_screen_factory)
    {
        $this->table_screen_factory = $table_screen_factory;
    }

    public function can_create(ListKey $key): bool
    {
        return $this->table_screen_factory->can_create($key);
    }

    public function create(ListKey $key, array $settings = []): ListScreen
    {
        if ( ! $this->can_create($key)) {
            throw InvalidListScreenException::from_invalid_key($key);
        }

        $table_screen = $this->table_screen_factory->create($key);

        // TODO
        $date = $settings['date'] ?? new DateTime();

        if (is_string($date)) {
            $date = DateTime::createFromFormat('Y-m-d H:i:s', $date);
        }

        return new ListScreen(
            new ListScreenId($settings['list_id']),
            $this->get_columns($table_screen, $settings['columns'] ?? []) ?: $this->get_default_columns($table_screen),
            $table_screen,
            $settings['preferences'] ?? [],
            $settings['title'] ?? '',
            $date
        );
    }

    private function get_columns(TableScreen $table_screen, array $settings): array
    {
        $columns = [];

        $column_factory = new ColumnFactory($table_screen);

        foreach ($settings as $name => $data) {
            $data['name'] = (string)$name;

            $columns[] = $column_factory->create($data);
        }

        return array_filter($columns);
    }

    private function get_default_columns(TableScreen $table_screen): array
    {
        $columns = [];

        $column_factory = new ColumnFactory($table_screen);

        foreach ($table_screen->get_columns() as $column) {
            if ($column->is_original()) {
                $columns[] = $column_factory->create([
                    'type'  => $column->get_type(),
                    'label' => $column->get_label(),
                ]);
            }
        }

        return $columns;
    }

}