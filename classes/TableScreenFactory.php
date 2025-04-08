<?php

declare(strict_types=1);

namespace AC;

use AC\Type\TableId;
use WP_Screen;

interface TableScreenFactory
{

    public function create(TableId $id): TableScreen;

    public function can_create(TableId $id): bool;

    public function create_from_wp_screen(WP_Screen $screen): TableScreen;

    public function can_create_from_wp_screen(WP_Screen $screen): bool;

}