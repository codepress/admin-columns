<?php

declare(strict_types=1);

namespace AC;

use AC\Type\ListKey;
use WP_Screen;

interface TableScreenFactory
{

    public function create(ListKey $key): TableScreen;

    public function can_create(ListKey $key): bool;

    public function create_from_wp_screen(WP_Screen $screen): TableScreen;

    public function can_create_from_wp_screen(WP_Screen $screen): bool;

}