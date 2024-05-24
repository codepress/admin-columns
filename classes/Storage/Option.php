<?php

namespace AC\Storage;

final class Option implements KeyValuePair
{

    protected $key;

    public function __construct(string $key)
    {
        $this->key = $key;
    }

    public function get()
    {
        return get_option($this->key);
    }

    public function save($value): void
    {
        update_option($this->key, $value, false);
    }

    public function delete(): void
    {
        delete_option($this->key);
    }

    public function exists(): bool
    {
        return false !== $this->get();
    }

}