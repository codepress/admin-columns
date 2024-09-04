<?php

namespace AC\Storage\Repository;

use AC\Storage\Option;
use AC\Type\ListKey;

// TODO create cached version
final class DefaultColumnsRepository
{

    private function get_storage(ListKey $key): Option
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
        return $this->get_cached_storage($key)[$type] ?? null;
    }

    public function find_all(ListKey $key): array
    {
        $columns = $this->get_cached_storage($key);

        unset($columns['cb']);

        return $columns;
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
        return $this->get_storage($key)->get() ?: [];
    }

}