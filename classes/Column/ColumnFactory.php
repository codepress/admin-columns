<?php

declare(strict_types=1);

namespace AC\Column;

use AC\Column;
use AC\Setting\Config;

interface ColumnFactory
{

    public function create(Config $config): Column;
}