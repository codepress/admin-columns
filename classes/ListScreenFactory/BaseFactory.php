<?php

declare(strict_types=1);

namespace AC\ListScreenFactory;

use AC\Exception\InvalidListScreenException;
use AC\ListScreen;
use AC\ListScreenFactory;
use DateTime;
use WP_Screen;

abstract class BaseFactory implements ListScreenFactory
{

    protected function add_settings(ListScreen $list_screen, array $settings): ListScreen
    {
        $columns = $settings['columns'] ?? [];
        $preferences = $settings['preferences'] ?? [];
        $group = $settings['group'] ?? '';
        $date = $settings['date'] ?? new DateTime();
        $segments = $settings['segments'] ?? [];

        if (is_string($date)) {
            $date = DateTime::createFromFormat('Y-m-d H:i:s', $date);
        }

        $list_screen->set_title($settings['title'] ?? '');
        $list_screen->set_layout_id($settings['list_id'] ?? '');
        $list_screen->set_preferences($preferences ?: []);
        $list_screen->set_segments($segments);
        $list_screen->set_settings($columns ?: []);
        $list_screen->set_updated($date);

        if ($group) {
            $list_screen->set_group($group);
        }

        return $list_screen;
    }

    public function create(string $key, array $settings = []): ListScreen
    {
        if ( ! $this->can_create($key)) {
            throw InvalidListScreenException::from_invalid_key($key);
        }

        return $this->add_settings($this->create_list_screen($key), $settings);
    }

    public function create_from_wp_screen(WP_Screen $screen, array $settings = []): ListScreen
    {
        if ( ! $this->can_create_from_wp_screen($screen)) {
            throw InvalidListScreenException::from_invalid_screen($screen);
        }

        return $this->add_settings($this->create_list_screen_from_wp_screen($screen), $settings);
    }

    abstract protected function create_list_screen(string $key): ListScreen;

    abstract protected function create_list_screen_from_wp_screen(WP_Screen $screen): ListScreen;

}