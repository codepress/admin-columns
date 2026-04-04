<?php

declare(strict_types=1);

namespace AC\Helper\Select;

interface Paginated
{

    public function get_total_pages(): int;

    public function get_page(): int;

    public function is_last_page(): bool;

}