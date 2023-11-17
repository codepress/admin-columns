<?php

declare(strict_types=1);

namespace AC;

class ColumnFactory
{

    private $table_screen;

    public function __construct(TableScreen $table_screen)
    {
        $this->table_screen = $table_screen;
    }

    public function create(array $settings): ?Column
    {
        $type = $settings['type'] ?? null;

        if ( ! $type) {
            return null;
        }

        $column = $this->get_column((string)$type, $settings);

        if ( ! $column) {
            return null;
        }

        if ($this->table_screen instanceof PostType) {
            $column->set_post_type($this->table_screen->get_post_type());
        }

        if ($this->table_screen instanceof Taxonomy) {
            $column->set_taxonomy($this->table_screen->get_taxonomy());
        }

        $column->set_meta_type((string)$this->table_screen->get_meta_type());

        // TODO do_action('ac/list_screen/column_created', $column, $this);

        return $column;
    }

    private function get_column(string $type, array $settings): ?Column
    {
        $column_types = $this->table_screen->get_columns();

        // TODO do_action('ac/column_types', $this);

        foreach ($column_types as $column) {
            $column = clone $column;

            if ($column->get_type() !== $type) {
                continue;
            }

            $column->set_options($settings);

            if ($column->is_original()) {

                if ( isset($settings['label'])) {
                    $column->set_label($settings['label']);
                }

                return $column->set_name($type)
                              ->set_group('default');
            }

            return $column->set_name((string)$settings['name'])
                          ->set_group('custom');
        }

        return null;
    }

}