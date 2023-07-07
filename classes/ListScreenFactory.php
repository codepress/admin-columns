<?php

declare(strict_types=1);

namespace AC;

use WP_Screen;

interface ListScreenFactory
{

    public function can_create(string $key): bool;

    public function create(string $key, array $settings = []): ListScreen;

    public function can_create_from_wp_screen(WP_Screen $screen): bool;

    public function create_from_wp_screen(WP_Screen $screen, array $settings = []): ListScreen;

}