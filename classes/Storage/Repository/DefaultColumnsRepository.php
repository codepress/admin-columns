<?php

namespace AC\Storage\Repository;

use AC\Storage\Option;
use AC\Type\ColumnId;
use AC\Type\DefaultColumn;
use AC\Type\DefaultColumns;
use AC\Type\TableId;

final class DefaultColumnsRepository
{

    private function storage(TableId $id): Option
    {
        return new Option(
            sprintf('ac_columns_default_%s', $id)
        );
    }

    public function update(TableId $id, DefaultColumns $columns): void
    {
        $data = [];

        foreach ($columns as $column) {
            $args = [
                'label' => $column->get_label(),
            ];

            if ($column->is_sortable()) {
                $args['sortable'] = true;
            }

            $data[$column->get_name()] = $args;
        }

        $data
            ? $this->storage($id)->save($data)
            : $this->delete($id);
    }

    public function exists(TableId $id): bool
    {
        return false !== $this->storage($id)->get();
    }

    public function delete(TableId $id): void
    {
        $this->storage($id)
             ->delete();
    }

    public function find(TableId $id, ColumnId $column_id): ?DefaultColumn
    {
        $column_name = (string)$column_id;

        $data = $this->get_cached($id)[$column_name] ?? null;

        return $data
            ? $this->create_column($column_name, $data)
            : null;
    }

    public function find_all(TableId $id): DefaultColumns
    {
        $columns = [];

        foreach ($this->get_cached($id) as $column_name => $column_data) {
            if ('cb' === $column_name) {
                continue;
            }

            $columns[] = $this->create_column($column_name, $column_data);
        }

        return new DefaultColumns($columns);
    }

    private function create_column(string $column_name, array $data): DefaultColumn
    {
        return new DefaultColumn(
            $column_name,
            (string)($data['label'] ?? ''),
            (bool)($data['sortable'] ?? false)
        );
    }

    private function get_cached(TableId $id): array
    {
        static $cached_storage;

        if ( ! isset($cached_storage[(string)$id])) {
            $cached_storage[(string)$id] = $this->get($id);
        }

        return $cached_storage[(string)$id];
    }

    private function get(TableId $id): array
    {
        return $this->storage($id)->get() ?: [];
    }

}