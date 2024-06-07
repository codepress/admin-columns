<?php

declare(strict_types=1);

namespace AC\Storage;

final class GeneralOption
{

    private const KEY = 'cpac_general_options';

    public function save(string $key, string $value): void
    {
        $data = $this->get();

        $data[$key] = $value;

        $this->update($data);
    }

    public function find(string $key): ?string
    {
        return $this->get()[$key] ?? null;
    }

    public function remove(string $key): void
    {
        $data = $this->get();

        unset($data[$key]);

        $data
            ? $this->update($data)
            : $this->delete();
    }

    private function get(): array
    {
        return get_option(self::KEY, []) ?: [];
    }

    private function update(array $data): void
    {
        update_option(self::KEY, $data);
    }

    private function delete(): void
    {
        delete_option(self::KEY);
    }

}