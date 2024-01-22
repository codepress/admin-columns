<?php

namespace AC\Storage;

class Option implements OptionData
{

    protected $key;

    public function __construct(string $key)
    {
        $this->key = $key;
    }

    public function get(array $args = [])
    {
        $args = array_merge([
            'default' => false,
        ], $args);

        wp_cache_delete($this->key, 'options');

        return get_option($this->key, $args['default']);
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