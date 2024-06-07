<?php

namespace AC\Storage;

final class SiteOption implements KeyValuePair
{

    protected $key;

    public function __construct(string $key)
    {
        $this->key = $key;
    }

    public function get()
    {
        return get_site_option($this->key);
    }

    public function save($value): bool
    {
        return update_site_option($this->key, $value);
    }

    public function delete(): bool
    {
        return delete_site_option($this->key);
    }

    public function exists(): bool
    {
        return false !== $this->get();
    }

}