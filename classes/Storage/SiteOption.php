<?php

declare(strict_types=1);

namespace AC\Storage;

class SiteOption implements OptionData
{

    protected string $key;

    public function __construct(string $key)
    {
        $this->key = $key;
    }

    public function get()
    {
        wp_cache_delete($this->key, 'site-options');

        return get_site_option($this->key);
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