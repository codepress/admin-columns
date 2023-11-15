<?php

declare(strict_types=1);

namespace AC;

class ColumnFactory
{

    private $table_screen;

    private $columns_repository;

    public function __construct(TableScreen $table_screen, DefaultColumnsRepository $columns_repository)
    {
        $this->table_screen = $table_screen;
        $this->columns_repository = $columns_repository;
    }

    public function create(array $settings): ?Column
    {
        $type = $settings['type'] ?? null;

        if ( ! $type) {
            return null;
        }

        $column = $this->get_column_from_table_screen($type, $settings);

        if ( ! $column) {
            $column = $this->get_column_from_default_columns($type, $settings);
        }

        // TODO do_action('ac/list_screen/column_created', $column, $this);

        return $column;
    }

    private function get_column_from_table_screen(string $type, array $settings): ?Column
    {
        $column_types = $this->table_screen->get_columns();

        // TODO do_action('ac/column_types', $this);

        foreach ($column_types as $column) {
            if ($column->get_type() === $type) {
                $column->set_table_screen($this->table_screen)
                       ->set_name($settings['name'])
                       ->set_options($settings)
                       ->set_table_screen($this->table_screen);

                if ($column->is_original()) {
                    $column->set_name($type);
                    $column->set_label($this->get_original_label($type));
                }

                return $column;
            }
        }

        return null;
    }

    private function get_original_label(string $type): string
    {
        return $this->columns_repository->get((string)$this->table_screen->get_key())[$type]
               ?? __('Default', 'codepress-admin-columns');
    }

    private function get_column_from_default_columns(string $type, array $settings): ?Column
    {
        foreach ($this->columns_repository->get((string)$this->table_screen->get_key()) as $_type => $label) {
            if ($_type === $type) {
                return (new Column())->set_type($type)
                                     ->set_name($type)
                                     ->set_original(true)
                                     ->set_label($label)
                                     ->set_options($settings)
                                     ->set_table_screen($this->table_screen);
            }
        }

        return null;
    }

}