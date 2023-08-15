<?php

namespace AC;

class DefaultColumnsRepository
{

    private const OPTIONS_KEY = 'cpac_options_';

    private function get_option_name(string $list_screen_key): string
    {
        return self::OPTIONS_KEY . $list_screen_key . "__default";
    }

    public function update(string $list_screen_key, array $columns): void
    {
        update_option($this->get_option_name($list_screen_key), $columns, false);
    }

    public function exists(string $list_screen_key): bool
    {
        return false !== get_option($this->get_option_name($list_screen_key));
    }

    public function get(string $list_screen_key): array
    {
        return get_option($this->get_option_name($list_screen_key), []);
    }

    public function delete(string $list_screen_key): void
    {
        delete_option($this->get_option_name($list_screen_key));
    }

}