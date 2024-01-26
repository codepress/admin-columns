<?php

namespace AC\Storage;

class SiteOption implements OptionData
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

        wp_cache_delete($this->key, 'site-options');

        return get_site_option($this->key, $args['default']);
    }

    public function save($value): void
    {
        update_site_option($this->key, $value);
    }

    public function delete(): void
    {
        delete_site_option($this->key);
    }

}