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

class BaseFactory implements ListScreenFactory
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

    public function create_from_encoded_data(ListKey $key, array $encoded_data = []): ListScreen
    {
        if ( ! $this->can_create($key)) {
            throw InvalidListScreenException::from_invalid_key($key);
        }

        $table_screen = $this->table_screen_factory->create($key);

        return new ListScreen(
            new ListScreenId($encoded_data['list_id']),
            $encoded_data['title'],
            $table_screen,
            $this->get_columns($table_screen, $encoded_data),
            $encoded_data['preferences'] ?? [],
            $this->get_date($encoded_data)
        );
    }

    protected function get_date(array $settings): DateTime
    {
        $date = $settings['date'] ?? new DateTime();

        if (is_string($date)) {
            return DateTime::createFromFormat('Y-m-d H:i:s', $date);
        }

        return $date;
    }

    protected function get_columns(TableScreen $table_screen, array $settings): array
    {
        $columns = [];

        $column_factory = new ColumnFactory($table_screen);

        foreach ($settings['columns'] as $name => $data) {
            // TODO is $name key necessary?
            if ( ! isset($data['name'])) {
                $data['name'] = (string)$name;
            }

            $column = $column_factory->create($data);

            if ($column) {
                $columns[] = $column;
            }
        }

        // TODO remove
        // use original columns when empty
        //        if ( ! $columns) {
        //            foreach ($table_screen->get_columns() as $column) {
        //                if ( ! $column->is_original()) {
        //                    continue;
        //                }
        //                $column = $column_factory->create([
        //                    'type'  => $column->get_type(),
        //                    'label' => $column->get_label(),
        //                ]);
        //
        //                if ($column) {
        //                    $columns[] = $column;
        //                }
        //            }
        //        }

        return $columns;
    }

}