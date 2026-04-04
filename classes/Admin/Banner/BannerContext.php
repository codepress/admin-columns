<?php

declare(strict_types=1);

namespace AC\Admin\Banner;

use AC\TableScreen;

interface BannerContext
{

    public function is_active(TableScreen $table_screen): bool;

    /**
     * Lower number = higher priority.
     */
    public function get_priority(): int;

    /**
     * @return array<string, mixed>
     */
    public function get_arguments(TableScreen $table_screen): array;

}
