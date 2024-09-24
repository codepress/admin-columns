<?php

declare(strict_types=1);

namespace AC\Storage;

class Option implements OptionData
{

    protected string $key;

    public function __construct(string $key)
    {
        $this->key = $key;
    }

    public function get()
    {
        wp_cache_delete($this->key, 'options');

        return get_option($this->key, false);
    }

    public function save($value): void
    {
        update_option($this->key, $value, false);
    }

    public function delete(): void
    {
        delete_option($this->key);
    }

}