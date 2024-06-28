<?php

namespace AC\Storage\Repository;

use AC\Storage\Option;
use AC\Type\ListKey;

class DefaultColumnsRepository
{

    public function get_storage(ListKey $key): Option
    {
        return new Option(
            sprintf('cpac_options_%s__default', $key)
        );
    }

    public function update(ListKey $key, array $columns): void
    {
        $this->get_storage($key)
             ->save($columns);
    }

    public function exists(ListKey $key): bool
    {
        return false !== $this->get_storage($key)->get();
    }

    public function delete(ListKey $key): void
    {
        $this->get_storage($key)
             ->delete();
    }

    public function find(string $type, ListKey $key): ?string
    {
        return $this->get($key)[$type] ?? null;
    }

    public function find_all(ListKey $key): array
    {
        $columns = $this->get($key);

        unset($columns['cb']);

        return $columns;
    }

    private function get(ListKey $key): array
    {
        return $this->get_storage($key)->get() ?: [];
    }

}