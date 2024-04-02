<?php

namespace AC\Column;

use AC;
use AC\Setting\Config;

interface ColumnFactory
{

    public function create(Config $config): AC\Column;

    public function get_column_type(): string;

}