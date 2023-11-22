<?php

declare(strict_types=1);

namespace AC;

use AC\ListScreen\ManageValue;
use AC\Type\Labels;
use AC\Type\ListKey;
use AC\Type\Uri;

// TODO make manage_value an abstract method
abstract class TableScreen implements ManageValue
{

    protected $key;

    protected $screen_id;

    protected $network;

    protected $columns;

    public function __construct(ListKey $key, string $screen_id, array $columns, bool $network = false)
    {
        $this->key = $key;
        $this->screen_id = $screen_id;
        $this->network = $network;
        $this->columns = $columns;
    }

    public function get_key(): ListKey
    {
        return $this->key;
    }

    public function is_network(): bool
    {
        return $this->network;
    }

    public function get_screen_id(): string
    {
        return $this->screen_id;
    }

    abstract public function get_heading_hookname(): string;

    abstract public function get_labels(): Labels;

    abstract public function get_query_type(): string;

    // TODO interface

    abstract public function get_meta_type(): MetaType;

    abstract public function get_attr_id(): string;

    abstract public function get_url(): Uri;

    abstract public function get_group(): string;

    public function get_columns(): array
    {
        // original WP columns
        $columns = (new DefaultColumnsRepository($this->key))->find_all();

        foreach ($this->columns as $column_fqn) {
            /**
             * @var Column $columnn
             */
            $column = new $column_fqn();

            $original = $columns[$column->get_type()] ?? null;

            // skip original column types that do not exist
            if ( ! $original && $column->is_original()) {
                continue;
            }

            if ($original) {
                $column->set_label($original->get_label())
                       ->set_group($original->get_group());
            }

            $columns[$column->get_type()] = $column;
        }

        $columns = array_values($columns);

        //  TODO check usages: do_action('ac/column_types', $this);
        return (array)apply_filters('ac/column_types', $columns, $this);
    }

}