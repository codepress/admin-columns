<?php

declare(strict_types=1);

namespace AC;

class ColumnFactory
{

    private $column_types_factory;

    public function __construct(ColumnTypesFactory $column_types_factory)
    {
        $this->column_types_factory = $column_types_factory;
    }

    private function get_column_types(TableScreen $table_screen): ColumnTypeCollection
    {
        static $types;

        $key = (string)$table_screen->get_key();

        if ( ! isset($types[$key])) {
            $types[$key] = $this->column_types_factory->create($table_screen);
        }

        return $types[$key];
    }

    public function create(TableScreen $table_screen, array $settings): ?Column
    {
        $type = $settings['type'] ?? null;

        if ( ! $type) {
            return null;
        }

        $column_types = $this->get_column_types($table_screen);

        $column = $this->create_column($column_types, (string)$type, $settings);

        if ( ! $column) {
            return null;
        }

        if ($table_screen instanceof PostType) {
            $column->set_post_type($table_screen->get_post_type());
        }

        if ($table_screen instanceof Taxonomy) {
            $column->set_taxonomy($table_screen->get_taxonomy());
        }

        if ($table_screen instanceof TableScreen\MetaType) {
            $column->set_meta_type((string)$table_screen->get_meta_type());
        }

        $column->set_list_key($table_screen->get_key());

        // TODO rename table_screen
        do_action('ac/list_screen/column_created', $column, $table_screen);

        return $column;
    }

    private function create_column(ColumnTypeCollection $column_types, string $type, array $settings): ?Column
    {
        foreach ($column_types as $column_type) {
            if ($column_type->get_type() !== $type) {
                continue;
            }

            return $this->create_from_column_type($column_type, $settings);
        }

        return null;
    }

    private function create_from_column_type(Column $column_type, array $settings): Column
    {
        $column = clone $column_type;

        $column->set_options($settings);

        if ($column->is_original()) {
            if (isset($settings['label'])) {
                $column->set_label($settings['label']);
            }

            return $column->set_name($column_type->get_type())
                          ->set_group('default');
        }

        return $column->set_name((string)$settings['name'])
                      ->set_group('custom');
    }

}