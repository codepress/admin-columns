<?php

namespace AC\Storage\Repository;

use AC\Storage\Option;
use AC\Type\DefaultColumn;
use AC\Type\DefaultColumns;
use AC\Type\ListKey;

final class DefaultColumnsRepository
{

    private function storage(ListKey $key): Option
    {
        return new Option(
            sprintf('ac_columns_default_%s', $key)
        );
    }

    public function update(ListKey $key, DefaultColumns $columns): void
    {
        $storage = $this->storage($key);

        $data = $storage->get() ?: [];

        foreach ($columns as $column) {
            $args = [
                'label' => $column->get_label(),
            ];

            if ($column->is_sortable()) {
                $args['sortable'] = true;
            }

            $data[$column->get_name()] = $args;
        }

        $storage->save($data);
    }

    public function exists(ListKey $key): bool
    {
        return false !== $this->storage($key)->get();
    }

    public function delete(ListKey $key): void
    {
        $this->storage($key)
             ->delete();
    }

    public function find_all(ListKey $key): DefaultColumns
    {
        $columns = [];

        foreach ($this->get_cached_storage($key) as $column_name => $column_data) {
            if ('cb' === $column_name) {
                continue;
            }

            $columns[] = new DefaultColumn(
                $column_name,
                $column_data['label'],
                (bool)($column_data['sortable'] ?? false)
            );
        }

        return new DefaultColumns($columns);
    }

    private function get_cached_storage(ListKey $key): array
    {
        static $cached_storage;

        if ( ! isset($cached_storage[(string)$key])) {
            $cached_storage[(string)$key] = $this->get($key);
        }

        return $cached_storage[(string)$key];
    }

    private function get(ListKey $key): array
    {
        return $this->storage($key)->get() ?: [];
    }

}