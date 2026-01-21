<?php

namespace AC\Storage\Repository;

use AC\Storage\Option;
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

    public function find(TableId $id, string $type): ?OriginalColumn
    {
        $data = $this->get_cached($id);

        if ( ! $data) {
            return null;
        }

        $column_data = $data[$type] ?? null;

        return $column_data && is_array($column_data)
            ? $this->create_column($type, $column_data)
            : null;
    }

    public function find_all_cached(TableId $id): OriginalColumns
    {
        $columns = [];

        $data = $this->get_cached($id);

        if ($data) {
            foreach ($data as $type => $column_data) {
                if ('cb' === $type) {
                    continue;
                }

                $columns[] = $this->create_column($type, $column_data);
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

        foreach ($this->get($id) as $type => $data) {
            if ('cb' === $type) {
                continue;
            }

            $columns[] = $this->create_column($type, $data);
        }

        return new OriginalColumns($columns);
    }

    private function create_column(string $type, array $data): OriginalColumn
    {
        return new OriginalColumn(
            $type,
            (string)($data['label'] ?? ''),
            (bool)($data['sortable'] ?? false)
        );
    }

    private function get(TableId $id): array
    {
        return $this->storage($id)->get() ?: [];
    }

}