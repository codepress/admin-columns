<?php

namespace AC\Storage\Repository;

use AC\Storage\Option;
use AC\Type\ColumnId;
use AC\Type\OriginalColumn;
use AC\Type\OriginalColumns;
use AC\Type\TableId;

final class OriginalColumnsRepository
{

    private function storage(TableId $id): Option
    {
        return new Option(
            sprintf('_ac_columns_default_%s', $id)
        );
    }

    public function update(TableId $id, OriginalColumns $columns): void
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

        $this->storage($id)->save($data);
    }

    public function exists(TableId $id): bool
    {
        return false !== $this->get_cached($id);
    }

    public function delete(TableId $id): void
    {
        $this->storage($id)
             ->delete();
    }

    public function find(TableId $id, ColumnId $column_id): ?OriginalColumn
    {
        $data = $this->get_cached($id);

        if ( ! $data) {
            return null;
        }

        $column_name = (string)$column_id;

        $column_data = $data[$column_name] ?? null;

        return $column_data && is_array($column_data)
            ? $this->create_column($column_name, $column_data)
            : null;
    }

    public function find_all_cached(TableId $id): OriginalColumns
    {
        $columns = [];

        $data = $this->get_cached($id);

        if ($data) {
            foreach ($data as $column_name => $column_data) {
                if ('cb' === $column_name) {
                    continue;
                }

                $columns[] = $this->create_column($column_name, $column_data);
            }
        }

        return new OriginalColumns($columns);
    }

    private function get_cached(TableId $id)
    {
        static $cached_storage;

        if ( ! isset($cached_storage[(string)$id])) {
            $cached_storage[(string)$id] = $this->storage($id)->get();
        }

        return $cached_storage[(string)$id];
    }

    public function find_all(TableId $id): OriginalColumns
    {
        $columns = [];

        foreach ($this->get($id) as $column_name => $column_data) {
            if ('cb' === $column_name) {
                continue;
            }

            $columns[] = $this->create_column($column_name, $column_data);
        }

        return new OriginalColumns($columns);
    }

    private function create_column(string $column_name, array $data): OriginalColumn
    {
        return new OriginalColumn(
            $column_name,
            (string)($data['label'] ?? ''),
            (bool)($data['sortable'] ?? false)
        );
    }

    private function get(TableId $id): array
    {
        return $this->storage($id)->get() ?: [];
    }

}